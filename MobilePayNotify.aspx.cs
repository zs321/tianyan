using JRAPI.Common;
using JRAPI.Data;
using JRAPI.Interfaces;
using JRAPI.Model;
using JRAPI.Pay.Services.zzzfb;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text.RegularExpressions;
using System.Threading;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;

namespace JRAPI.Pay.Services.LwPay
{
    public partial class MobilePayNotify : System.Web.UI.Page
    {
        protected void Page_Load(object sender, EventArgs e)
        {
            string dt = JRRequest.GetString("dt");
            string mark = JRRequest.GetString("mark"); // ordermodel.BillNO;
            string money = JRRequest.GetString("money");
            string no = JRRequest.GetString("no");
            string type = JRRequest.GetString("type");
            string account = JRRequest.GetString("account");
            string sign = JRRequest.GetString("sign");
            string msg = string.Empty;
            string tores = string.Empty;
            Log.Info("Gaterequest", Request.Url.ToString() + "?dt=" + dt + "&mark=" + mark + "&money=" + money + "&type=" + type + "&account=" + account + "&no=" + no + "&sign=" + sign);
            //Log.Info("Gaterequest","::::"+ (mark.Length <= 10 && mark.First() == 'G' && mark.Last() == 'G').ToString());

            string url = string.Empty;
            try
            {
                if (!Alino.GetHave(no))
                {
                    //固码付款处理
                    NetGateModel model3 = NetGate.GetModel(172);
                    //备注不大于12位
                    if ((mark.Length <= 12 && mark.First() == 'G' && mark.Last() == 'G') || mark.Length <= 10)
                    {
                        url = model3.GateUrl.Replace("Api/pay/set.html", "Api/Client/aliApi") + "?key=123456&money=" +
                                              money.Replace("￥", "") + "&t=" + DateTime.Now.AddSeconds(5).ToString("yyyy-MM-dd HH:mm:ss") + "&b=" + mark +
                                              "&o=" + no + "&zfb=" + account;
                        tores = HttpService.Get(url);
                        //Log.Info("GateNot-G", url + "||||" + tores);
                        //model = Orders.getOrderByBzAndMoney(money, mark, account.Trim());
                        Alino.Add(no);
                        base.Response.Write("success 请求固码服务器 返回:" + tores);
                        base.Response.End();
                    }
                    //if (mark.Length <= 2)
                    //{
                    //    url = model3.GateUrl.Replace("Api/pay/set.html", "Api/Client/aliApi") + "?key=123456&money=" +
                    //                           money.Replace("￥", "") + "&t=" + DateTime.Now.AddSeconds(5).ToString("yyyy-MM-dd HH:mm:ss") + "&b=" + mark +
                    //                           "&o=" + no + "&zfb=" + account;
                    //    tores = HttpService.Get(url);
                    //    Log.Info("GateNot-No.", url + "||||" + tores);
                    //    //model = Orders.getOrderByBzAndMoney(money, mark, account.Trim());
                    //    Alino.Add(no);
                    //    base.Response.Write("success 请求固码服务器 返回:" + tores);
                    //    base.Response.End();
                    //}
                }
            }
            catch (ThreadAbortException e3) { }
            catch (Exception e2)
            {
                msg = "参数不正确   dt=" + dt + "&mark=" + mark + "&money=" + money + "&type=" + type + "&account=" + account + "&no=" + no + "&sig=" + sign;
                Log.Error("固定码转发", e2.ToString());
                if (!string.IsNullOrEmpty(url))
                {
                    tores = HttpService.Get(url);
                }
                base.Response.Write("success 固定码");
                base.Response.End();
            }
            string mpk = "";
            if (mark.IndexOf("=") != -1 || Utils.UrlDecode(mark).IndexOf("=")!= -1)
            {
                mpk = mark;
                mark = mark.Substring(0, mark.IndexOf("="));
            }
            OrdersModel model = Orders.GetModel(mark);
            if (model != null)
            {
                NetGateModel model2 = NetGate.GetModel(model.Gateid);
                if (model2 != null)
                {
                    //{\"account\":\"2897483365@qq.com\",\"dt\":\"1543481823475\",\"mark\":\"k181129165636100\",\"money\":\"1.00\",\"no\":\"20181129200040011100060021302577\",\"sign\":\"66633403551ecc9fd8ce15c7476ed140\",\"type\":\"alipay\"}
                    //        1543481823475k1811291656361001.0020181129200040011100060021302577alipay123456                                                                               66633403551ecc9fd8ce15c7476ed140

                    string newsign = "";
                    if (mpk.IndexOf("=") != -1 || Utils.UrlDecode(mpk).IndexOf("=") != -1)
                    {
                        newsign = Utils.MD5(dt + mpk + money + no + type + model2.GateUserKey, false);
                    }
                    else
                    {
                        newsign = Utils.MD5(dt + mark + money + no + type + model2.GateUserKey, false);
                    }
                    Log.Error("key", newsign+":::" + mark);
                    Log.Error("key", "" + ":::" + model2.GateUserKey);
                    // string newsign = System.Web.Security.FormsAuthentication.HashPasswordForStoringInConfigFile(dt + mark + money + no + type + model2.GateUserKey, "MD5");
                    if (sign == newsign)
                    {
                        //签名正确
                        string str = string.Format("orderid={0}&key={1}&account={2}", new object[]
                        {
                            mark,
                            model2.GateUserKey,
                            account
                        });
                        Regex r = new Regex("\\d+\\.?\\d*");
                        bool ismatch = r.IsMatch(money);
                        MatchCollection mc = r.Matches(money);
                        string result = string.Empty;
                        for (int i = 0; i < mc.Count; i++)
                        {
                            result += mc[i];
                        }

                        try
                        {
                            if (model.OrderMoney == decimal.Parse(money))
                            {
                                model.Realmoney = model.OrderMoney;// decimal.Parse(result);
                                model.OrderStatus = 1;
                                model.SuperNO = no;
                                model.GateMsg = str;
                                Orders.UpdateOrderStatus(model);
                                Zzzfb zz = new Zzzfb();
                                zz.Zz(model);
                                msg = "success 金额为：" + money + " 类型转换后的金额为：" + model.Realmoney.ToString();
                            }
                            else
                            {
                                msg = "付款金额不匹配！";
                            }
                        }
                        catch
                        {
                            msg = "金额转换失败！" + model.OrderMoney + "-->" + money;
                        }
                    }
                    else
                    {
                        //签名不正确
                        msg = "sign签名错误";
                    }
                }
                else
                {
                    msg = "网关配置不存在";
                }
            }
            else
            {
                msg = "success main";
            }
            base.Response.Write(msg);
            base.Response.End();
        }

        public static string MD5(string password)
        {
            byte[] textBytes = System.Text.Encoding.Default.GetBytes(password);
            try
            {
                System.Security.Cryptography.MD5CryptoServiceProvider cryptHandler;
                cryptHandler = new System.Security.Cryptography.MD5CryptoServiceProvider();
                byte[] hash = cryptHandler.ComputeHash(textBytes);
                string ret = "";
                foreach (byte a in hash)
                {
                    if (a < 16)
                        ret += "0" + a.ToString("x");
                    else
                        ret += a.ToString("x");
                }
                return ret;
            }
            catch
            {
                throw;
            }
        }

    }
}