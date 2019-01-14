
using System.Text;
using System.Web;
using System.Web.SessionState;
using JRAPI.Common;
using JRAPI.Model;
using Newtonsoft.Json.Linq;

namespace JRAPI.Interfaces.LwPay
{
    public class MobilePay : ICore
    {
        public string[,] getBankList()
        {
            return null;
        }

        public string[,] getFaceList()
        {
            return null;
        }
        private static HttpSessionState _session = HttpContext.Current.Session;
        public static void SetSession(string key, object value)
        {
            _session[key] = value;
        }
        public string orderSend(NetGateModel gatemodel, OrdersModel ordermodel)
        {
            /*
            string gateRecUrl = gatemodel.GateRecUrl;
            if (string.IsNullOrEmpty(gateRecUrl))
            {
                gateRecUrl = SysConfig.GetModel(1).PostBackUrl;
            }
            */

            string money = ordermodel.OrderMoney.ToString();
            string mark = ordermodel.BillNO;
            string oldmark = ordermodel.BillNO;

            string type = ordermodel.ChannelCode;  // alipay  为支付宝  wechat为微信
            string frist_type = type;
            if (type.ToUpper().IndexOf("WEIXIN") >= 0)
            {
                type = "wechat";
            }
            else
            {
                if (type.ToUpper().IndexOf("ALIPAY") >= 0)
                {
                    type = "alipay";
                }
            }
            string old = money;
            //Random r = new Random();
            //int num = r.Next(1, 5);
            //decimal fkje = decimal.Parse(money) - (((decimal)num) / 100);
            // decimal fkje = decimal.Parse(money);
            string GateUrl = gatemodel.GateUrl;
            string GateUserKey = gatemodel.GateUserKey;
            string url = gatemodel.GateUrl + "/getpay?money=" + money + "&mark=" + mark + "&type=" + type;

            //Log.Error("URL：：：：：：：：：：", "" + url);
            string text = HttpService.Get(url);
            Log.Error("a", text + "  |||||3   ");
            JObject jsonData = JObject.Parse(text);
            Log.Error("a", jsonData.ToString() + "  |||||3   ");
            string msg = (string)jsonData["msg"];
            if (msg == "获取成功")
            {
                string payurl = (string)jsonData["payurl"];
                mark = (string)jsonData["mark"];
                money = (string)jsonData["money"];
                type = (string)jsonData["type"];
                int error = 0;
                while (mark != oldmark)
                {
                    error++;
                    jsonData = JObject.Parse(text);
                    msg = (string)jsonData["msg"];
                    if (msg == "获取成功")
                    {
                        mark = (string)jsonData["mark"];
                        money = (string)jsonData["money"];
                        type = (string)jsonData["type"];
                    }
                    if (error >= 3)
                    {
                        return ("no");
                    }
                }
                if (type == "wechat")
                {
                    type = "1";
                }
                else
                {
                    type = "2";
                }
                if ("ALIPAYWAP" == frist_type.ToUpper())
                {
                    Data.Orders.SetQRCodeShow(ordermodel.BillNO);
                    string str = "http://mobile.qq.com/qrcode?url=" + payurl;
                    HttpContext.Current.Response.Redirect(payurl);
                }
                else
                {
                    string str = "Services/LwPay/MobileForm.aspx?money=" + old + "&pay_url=" + payurl + "&trade_no=" + mark + "&type=" + type + "&gateurl=" + GateUrl;
                    string str1 = "/Pay_MobilePay.html?date="+ Utils.EncodeBase64(Encoding.UTF8, "money=" + old + "&pay_url=" + payurl + "&trade_no=" + mark + "&type=" + type + "&gateurl=" + GateUrl);
                    HttpContext.Current.Response.Redirect(str);
                }
                
            }
            else
            {
                HttpContext.Current.Response.Write(msg);
                HttpContext.Current.Response.End();
            }
            return ("no");
        }
    }
}
