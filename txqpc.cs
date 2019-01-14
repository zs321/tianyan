namespace JRAPI.Interfaces
{
    using JRAPI.Common;
    using JRAPI.Data;
    using JRAPI.Model;
    using System;
    using System.Collections.Generic;
    using System.IO;
    using System.Net;
    using System.Security.Cryptography;
    using System.Text;
    using System.Web;
    using System.Linq;
    using Newtonsoft.Json.Linq;
    using System.Drawing;
    using LitJson;
    using System.Diagnostics;
    using System.Configuration;

    public class txqpc : ICore
    {
        public string[,] getBankList()
        {
            return null;
        }

        public string[,] getFaceList()
        {
            return null;
        }

        public string orderSend(NetGateModel gate, OrdersModel ordermodel)
        {
            //Log.Error("error", gate.ToString() + ":::::::::::::" + ordermodel.ToString());
            string gateRecUrl = gate.GateRecUrl;
            if (string.IsNullOrEmpty(gateRecUrl))
            {
                gateRecUrl = SysConfig.GetModel(1).PostBackUrl;
            }

            string pricea = ordermodel.OrderMoney.ToString();

            //string no_pirce = ",0,"; /*保留前后逗号*/

            string[] str = ordermodel.OrderMoney.ToString().Split('.');
            // string price = str[0]+".00";

            //if (ordermodel.OrderMoney < Convert.ToDecimal(0.01))
            //{
            //    HttpContext.Current.Response.Write("不能小于0.01元！");
            //    HttpContext.Current.Response.Write("<script>alert('不能小于0.01元！');window.close(); </script>");
            //    HttpContext.Current.Response.End();
            //}
            // if(pricea.IndexOf(".") > -1)            
            // {
            //      pricea = price;

            //   }
            //else
            //{
            //    decimal num = Math.Floor(TypeConverter.StrToDecimal(price, 0M));

            //    if (num < 10)
            //    {
            //        HttpContext.Current.Response.Write("<script>alert('请提交10的整数倍金额，重新输入金额后再提交，谢谢！');window.close(); </script>");
            //        HttpContext.Current.Response.End();
            //    }
            //    else
            //    {
            //        if (no_pirce.IndexOf("," + price + ",") > -1)
            //        {
            //            HttpContext.Current.Response.Write("<script>alert('不允许输入" + no_pirce + "这些金额');window.close(); </script>");
            //            HttpContext.Current.Response.End();
            //        }
            //        else
            //        {
            //            if (num > 3000)
            //            {
            //                HttpContext.Current.Response.Write("<script>alert('输入的金额不能大于3000');window.close(); </script>");
            //                HttpContext.Current.Response.End();
            //            }
            //            else
            //            {
            //                if (num < 1000)
            //                {
            //                    if (num % 10 != 0)
            //                    {
            //                        HttpContext.Current.Response.Write("<script>alert('小于1000金额只能提交10的整数倍');window.close(); </script>");
            //                        HttpContext.Current.Response.End();
            //                    }
            //                }

            //            }
            //        }
            //    }
            //}
            string num = ordermodel.account;
            ChannelModel model = null;
            NetGateModel mo = null;
            //Logs.WirteLog("group_", num +"___________0");

            bool isgroup = false;
            if (string.IsNullOrEmpty(num))
            {
                model = Channel.GetModelByMobile(ordermodel.ChannelCode, "JRAPI.Interfaces.LwPay.MobilePay");
                mo = NetGate.GetModel(model.GateID);
            }
            else
            {
                isgroup = true;
                NetGateGroupDataModel netGateGroupDataModel = NetGateGroup.GetDataModel(int.Parse(num));
                mo = NetGate.GetModel(int.Parse(netGateGroupDataModel.NetGateGroupData.Trim()));
                model = Channel.GetChannelModelToType(mo.Gateid.ToString(), ordermodel.ChannelCode);
            }

            Orders.SetOrderToGateid(mo == null ? ordermodel.Gateid : mo.Gateid, ordermodel.OrderID, model == null ? ordermodel.Channelid : model.Channelid);


            if (isgroup)
            {
                if (mo.GateCode == "JRAPI.Interfaces.tzzfb.tzzfb")
                {
                    ICore core = new tzzfb.tzzfb();
                    string remsg = core.orderSend(mo, ordermodel);
                    return remsg;
                }
            }


            string trade_type = "";

            string channelcode = Data.Channel.GetModel(ordermodel.Channelid).ChanneCode;
            switch (ordermodel.ChannelCode)
            {
                case "ALIPAY": trade_type = "ALIPAYPC"; break;
                case "WEIXIN": trade_type = "WECHATNATIVE"; break;
                case "ALIPAYF2F": trade_type = "ALIPAYF2F"; break;
                case "ALIPAYWAP": trade_type = "ALIPAYWAP"; break;
                default:
                    trade_type = channelcode;
                    break;
            }
            Orders.SetOrderUpdate(ordermodel.BillNO, mo == null ? "" : mo.Gateid.ToString());
            SortedDictionary<string, string> data = new SortedDictionary<string, string>();
            data["trade_type"] = trade_type;
            data["body"] = "购买商品-在线支付";
            data["attach"] = ordermodel.AssistStr;
            data["total_fee"] = pricea;
            data["return_url"] = gateRecUrl + "/Services/txqpc/jump.aspx";
            data["notice_url"] = gateRecUrl + "/Services/txqpc/callback.aspx";
            data["out_trade_no"] = ordermodel.BillNO;
            data["sign"] = CreateSign(data, gate.GateUserKey);
            data["net_gate_url"] = mo == null ? "" : mo.GateUrl;
            data["ali_account"] = mo == null ? "" : mo.GateRemark.Substring(0, mo.GateRemark.IndexOf(";") == -1 ? mo.GateRemark.Length : mo.GateRemark.IndexOf(";"));
            // Stopwatch sw = new Stopwatch();
            //sw.Start();
            Logs.WirteLog("gate", gate.GateUrl);
            string result = SkillCurl(gate.GateUrl, data, gate.GateUserKey);

            //sw.Stop();
            //TimeSpan ts2 = sw.Elapsed;
            //Console.WriteLine("Stopwatch总共花费{0}ms.", ts2.TotalMilliseconds);
            // Logs.WirteLog("172", ordermodel.BillNO+"---::::???---" + ts2.TotalMilliseconds.ToString());
            //Logs.WirteLog("result", result);

            JObject jsonObj = JObject.Parse(result);
            //Log.Error("jsonObj", jsonObj.ToString());
            string s = Pope(jsonObj, trade_type, ordermodel, pricea, model, mo);

            return s;
        }

        string Pope(JObject jsonObj, string trade_type, OrdersModel ordermodel, string pricea, ChannelModel model, NetGateModel mo)
        {
            string hosthttp = "http://" + JRRequest.GetHost();
            string sird = ConfigurationManager.AppSettings["isredirect"];
            //Logs.WirteLog("wangz0", jsonObj.ToString());
            if (jsonObj["return_code"].ToString().Equals("SUCCESS"))
            {
                 Logs.WirteLog("wangz0", jsonObj.ToString());
                if (jsonObj["bendi"].ToString() == "1")
                {
                    //string remark = jsonObj["note"].ToString();
                    //string qmoney = jsonObj["qmoney"].ToString();

                    if (trade_type == "ALIPAYF2F")
                    {
                        resulta = jsonObj["code_url"].ToString();
                    }
                    if (trade_type == "ALIPAYPC")
                    {
                        resulta = jsonObj["pay_url"].ToString();
                    }

                    if (ordermodel.ChannelCode == "ALIPAYWAP")
                    {
                        resulta = jsonObj["pay_url"].ToString();
                        Data.Orders.SetQRCodeShow(ordermodel.BillNO);
                        string deUrl = hosthttp + "/Services/ReRun.aspx?trade_no=" + ordermodel.BillNO + "&pay_url=" + resulta;
                        //Log.Info("wangz0", deUrl );
                        //string durl = "http://mobile.qq.com/qrcode?url=" + ;
                        deUrl = resulta;
                        if (!sird.Equals("1"))
                        {
                            HttpContext.Current.Response.Redirect(deUrl, false); HttpContext.Current.Response.End();
                        }
                        return deUrl;
                    }
                    if (trade_type == "WECHATNATIVE")
                    {
                        resulta = jsonObj["code_url"].ToString();
                        return ("MakeQRCode.aspx?data=" + HttpUtility.UrlEncode(resulta));
                    }
                    //return "http://mobile.qq.com/qrcode?url=" + resulta;
                    //string str1 = "/Services/LwPay/MobileForm.aspx?money=" + pricea + "&pay_url=" + resulta + "&trade_no=" + ordermodel.BillNO + "&type=2" + "&gateurl=";
                    string deUrl1 = "http://" + JRRequest.GetHost() + "/Services/ReRun.aspx?date=" + Utils.EncodeBase64(Encoding.UTF8, "trade_no=" + ordermodel.BillNO + "&pay_url=" + resulta);
                    string str12 = "/Pay_MobilePay.html?date=" + Utils.EncodeBase64(Encoding.UTF8, "money=" + pricea + "&pay_url=" + resulta + "&trade_no=" + ordermodel.BillNO + "&type=2" + "&gateurl=");
                    //HttpContext.Current.Response.Write(str12);

                    //string str1_1 = "/Services/LwPay/MobileForm.aspx?money=" + pricea + "&pay_url=" + deUrl1 + "&trade_no=" + ordermodel.BillNO + "&type=2" + "&gateurl=";
                    //Log.Info("wangz1", deUrl1 + ":::::" + str1_1);
                    if (!sird.Equals("1"))
                    {
                        HttpContext.Current.Response.Redirect(str12, false); HttpContext.Current.Response.End();
                    }
                    return hosthttp + str12;
                }
                else if (jsonObj["bendi"].ToString() == "2")
                {
                    JObject msgr = JObject.Parse(jsonObj["return_msg"].ToString());
                    //Log.Info("INFO", "bendi:::::" + jsonObj["bendi"].ToString());
                    string payurl = (string)msgr["payurl"];
                    string mark = (string)msgr["mark"];
                    string money = (string)msgr["money"];
                    string type = (string)msgr["type"];
                    //int error = 0;

                    //Orders.SetOrderUpdate(ordermodel.BillNO, money, "", "", "FALSE", mo == null ? "" : mo.GateRemark);
                    if (type == "wechat")
                    {
                        type = "1";
                    }
                    else
                    {
                        type = "2";
                    }


                    if ("ALIPAYWAP" == ordermodel.ChannelCode.ToUpper())
                    {
                        Data.Orders.SetQRCodeShow(ordermodel.BillNO);
                        //string str = "http://mobile.qq.com/qrcode?url=" + payurl;
                        string deUrl = hosthttp + "/Services/ReRun.aspx?trade_no=" + ordermodel.BillNO + "&pay_url=" + payurl;
                        //Log.Info("wangz2_0", deUrl);
                        string durl = "http://mobile.qq.com/qrcode?url=" + payurl;

                        deUrl = payurl;
                        if (!sird.Equals("1"))
                        {
                            HttpContext.Current.Response.Redirect(deUrl, false); HttpContext.Current.Response.End();
                        }
                        return deUrl;
                    }
                    else
                    {
                        string deUrl1 = "http://" + JRRequest.GetHost() + "/Services/ReRun.aspx?date=" + Utils.EncodeBase64(Encoding.UTF8, "trade_no=" + ordermodel.BillNO + "&pay_url=" + payurl);
                        // string str1 = "/Services/LwPay/MobileForm.aspx?money=" + pricea + "&pay_url=" + payurl + "&trade_no=" + mark + "&type=" + type + "&gateurl=" + (mo == null ? "" : mo.GateUrl).ToString();
                        string str12 = hosthttp + "/Pay_MobilePay.html?date=" + Utils.EncodeBase64(Encoding.UTF8, "money=" + pricea + "&pay_url=" + payurl + "&trade_no=" + mark + "&type=" + type + "&gateurl=" + (mo == null ? "" : mo.GateUrl).ToString());
                        //HttpContext.Current.Response.Write(str12);
                        //string str1_1 = "/Services/LwPay/MobileForm.aspx?money=" + pricea + "&pay_url=" + deUrl1 + "&trade_no=" + mark + "&type=" + type + "&gateurl=" + (mo == null ? "" : mo.GateUrl).ToString();
                        //Log.Info("wangz1_2", deUrl1 + ":::::" + str1_1);
                        if (!sird.Equals("1"))
                        {
                            HttpContext.Current.Response.Redirect(str12, false); HttpContext.Current.Response.End();
                        }
                        return str12;

                    }
                }
                else if (jsonObj["bendi"].ToString() == "5")
                {
                    string ucode = jsonObj["uCode"].ToString();
                    pricea = ordermodel.OrderMoney.ToString();
                    if (ordermodel.ChannelCode == "ALIPAYF2F")
                    {
                        resulta = jsonObj["code_url"].ToString();
                    }
                    if (ordermodel.ChannelCode == "ALIPAY")
                    {
                        resulta = jsonObj["pay_url"].ToString();
                    }

                    if (ordermodel.ChannelCode == "ALIPAYWAP")
                    {
                        resulta = jsonObj["pay_url"].ToString();
                        Data.Orders.SetQRCodeShow(ordermodel.BillNO);
                        HttpContext.Current.Application.Add(ordermodel.BillNO + "_1", resulta);
                        string deUrl = hosthttp + "/Services/ReRun.aspx?trade_no=" + ordermodel.BillNO + "&pay_url=" + Utils.UrlEncode(resulta);
                        string deUrltest = hosthttp + "/Services/ReRun.aspx?date=" + Utils.EncodeBase64(Encoding.UTF8, "trade_no=" + ordermodel.BillNO + "&pay_url=" + resulta);

                        //string a = Utils.UrlEncode(deUrltest);

                        //string prtUrl = header + Utils.UrlEncode(refin + a);
                        if (!sird.Equals("1"))
                        {
                            HttpContext.Current.Response.Redirect(deUrltest, false);
                            HttpContext.Current.Response.End();
                        }
                        return deUrltest;
                    }

                    HttpContext.Current.Application.Add(ordermodel.BillNO + "_1", resulta);
                    //return "http://mobile.qq.com/qrcode?url=" + resulta;
                    string deUrl1 = "http://" + JRRequest.GetHost() + "/Services/ReRun.aspx?date=" + Utils.EncodeBase64(Encoding.UTF8, "trade_no=" + ordermodel.BillNO + "&pay_url=" + resulta);

                    string p_s = resulta.Substring(resulta.IndexOf("&userId"));
                    string spps = p_s.Replace("&userId=", "").Replace("userId=", "");
                    string uid = spps.Substring(0, spps.IndexOf("&memo=") - 1);

                    //deUrl1 = "http://" + JRRequest.GetHost() + "/Services/ReRun.aspx?P="+ pricea + "," + uid + "," + ucode;
                    string str12 = "/Pay_MobilePay.html?date=" + Utils.EncodeBase64(Encoding.UTF8, "money=" + pricea + "&pay_url=" + deUrl1 + "&trade_no=" + ordermodel.BillNO + "&type=2" + "&bendi=5" + "&gateurl="/*&ucode=" + ucode*/);

                    if (!sird.Equals("1"))
                    {
                        HttpContext.Current.Response.Redirect(str12, false);
                    }
                    return hosthttp + str12;
                }
                else if (jsonObj["bendi"].ToString() == "6")
                {
                    string totel_fee = jsonObj["qmoney"].ToString();
                    string zkl = jsonObj["kouling"].ToString();
                    string no = ordermodel.BillNO;
                    string ucode = jsonObj["note"].ToString();
                    Data.Orders.SetQRCodeShow(ordermodel.BillNO);
                    string str12 = hosthttp + "/Services/tzzfb/htmlfrom.aspx?date=" +Utils.UrlEncode(totel_fee + "," + no +","+ zkl+","+ ucode);
                    if (!sird.Equals("1"))
                    {
                        HttpContext.Current.Response.Redirect(str12, false); HttpContext.Current.Response.End();
                    }
                    return str12;
                }
                else
                {

                    if (!sird.Equals("1"))
                    {
                        HttpContext.Current.Response.Write("二维码获取失败,请重试！" + jsonObj["return_msg"].ToString());
                        HttpContext.Current.Response.End();
                    }
                    return "二维码获取失败,请重试！" + jsonObj["return_msg"].ToString();
                }
            }
            else
            {
                if (!sird.Equals("1"))
                {
                    HttpContext.Current.Response.Write("二维码获取失败, 请重试！" + jsonObj["return_msg"].ToString());
                    HttpContext.Current.Response.End();
                }
                return "二维码获取失败, 请重试！" + jsonObj["return_msg"].ToString();
            }
        }


        public Bitmap getImageFromNet(string url)
        {
            Bitmap img;
            try
            {
                WebRequest request = WebRequest.Create(url);
                using (WebResponse response = request.GetResponse())
                {
                    img = (Bitmap)Bitmap.FromStream(response.GetResponseStream());
                }

            }
            catch
            {
                img = null;
            }

            return img;
        }

        public static bool ChekSign(string sign, SortedDictionary<string, string> parameters, string secret)
        {
            NetGateModel gate = new NetGateModel();
            if (parameters.ContainsKey("sign")) parameters["sign"] = "";

            secret = string.IsNullOrEmpty(secret) ? gate.GateUserKey : secret;

            string signText = ToUrlParameter(parameters);
            signText += "&key=" + secret;

            MD5CryptoServiceProvider provider = new MD5CryptoServiceProvider();
            string str = BitConverter.ToString(provider.ComputeHash(Encoding.UTF8.GetBytes(signText)));
            provider.Clear();

            string signInfo = str.Replace("-", null).ToUpper();

            return signInfo.Equals(sign.ToUpper());
        }


        public static string CreateSign(SortedDictionary<string, string> dic, string secret)
        {
            NetGateModel gate = new NetGateModel();
            if (dic.ContainsKey("sign")) dic["sign"] = "";
            secret = string.IsNullOrEmpty(secret) ? gate.GateUserKey : secret;

            string signText = ToUrlParameter(dic);
            signText += "&key=" + secret;

            MD5CryptoServiceProvider provider = new MD5CryptoServiceProvider();
            string str = BitConverter.ToString(provider.ComputeHash(Encoding.UTF8.GetBytes(signText)));
            provider.Clear();

            string signInfo = str.Replace("-", null).ToUpper();

            return signInfo;
        }

        public static string ToUrlParameter(SortedDictionary<string, string> data)
        {
            string str = "";
            foreach (KeyValuePair<string, string> keyValuePair in data)
            {
                if (!keyValuePair.Key.Equals("sign"))
                {
                    str += keyValuePair.Key + "=" + keyValuePair.Value + "&";
                }
            }

            str = str.TrimEnd('&');

            return str;
        }

        public static string SkillCurl(string url, SortedDictionary<string, string> dictionary, string secret)
        {
            dictionary["sign"] = CreateSign(dictionary, secret);

            string parameters = dictionary.Aggregate("", (current, item) => current + (item.Key + "=" + item.Value + "&"));
            parameters = parameters.TrimEnd('&');

            byte[] bytes = Encoding.UTF8.GetBytes(parameters);

            HttpWebRequest request = (HttpWebRequest)WebRequest.Create(url);
            request.Method = "POST";
            request.Timeout = 10000;
            request.ContentType = "application/x-www-form-urlencoded";
            request.ContentLength = bytes.Length;

            Stream stream = request.GetRequestStream();
            stream.Write(bytes, 0, bytes.Length);
            stream.Close();
            HttpWebResponse response = null;
            response = (HttpWebResponse)request.GetResponse();
            Stream stream1 = response.GetResponseStream();
            if (stream1 == null) return "";

            StreamReader reader = new StreamReader(stream1, Encoding.UTF8);

            return reader.ReadToEnd();
        }


        public string qrurl { get; set; }

        public string resulta { get; set; }

        //internal string orderSendFrist(NetGateModel gate, OrdersModel ordermodel, out JObject jsonObjDate)
        //{
        //    string gateRecUrl = gate.GateRecUrl;
        //    if (string.IsNullOrEmpty(gateRecUrl))
        //    {
        //        gateRecUrl = SysConfig.GetModel(1).PostBackUrl;
        //    }

        //    //Logs.WirteLog("DATE", gateRecUrl);

        //    string pricea = ordermodel.OrderMoney.ToString();

        //    //string no_pirce = ",0,"; /*保留前后逗号*/

        //    string[] str = ordermodel.OrderMoney.ToString().Split('.');
        //    // string price = str[0]+".00";

        //    if (ordermodel.OrderMoney < Convert.ToDecimal(0.01))
        //    {
        //        HttpContext.Current.Response.Write("不能小于0.01元！");
        //        HttpContext.Current.Response.Write("<script>alert('不能小于0.01元！');window.close(); </script>");
        //        HttpContext.Current.Response.End();
        //    }

        //    string trade_type = "";
        //    if (ordermodel.ChannelCode == "ALIPAY") { trade_type = "ALIPAYPC"; }
        //    if (ordermodel.ChannelCode == "WEIXIN") { trade_type = "WECHATNATIVE"; }
        //    if (ordermodel.ChannelCode == "ALIPAYF2F") { trade_type = "ALIPAYF2F"; }
        //    if (ordermodel.ChannelCode == "ALIPAYWAP") { trade_type = "ALIPAYWAP"; }
        //    // Logs.WirteLog("trade_type", ordermodel.ChannelCode + "=" + trade_type);

        //    SortedDictionary<string, string> data = new SortedDictionary<string, string>();
        //    data["trade_type"] = trade_type;
        //    data["body"] = "购买商品-在线支付";
        //    data["attach"] = ordermodel.AssistStr;
        //    data["total_fee"] = pricea;
        //    data["return_url"] = gateRecUrl.Replace("paybank.aspx/", "") + "/Services/txqpc/jump.aspx";
        //    data["notice_url"] = gateRecUrl.Replace("paybank.aspx/", "") + "/Services/txqpc/callback.aspx";
        //    data["out_trade_no"] = ordermodel.BillNO;
        //    data["sign"] = CreateSign(data, gate.GateUserKey);

        //    string result = SkillCurl(gate.GateUrl, data, gate.GateUserKey);
        //    //Log.Info("rest", result);
        //    JObject jsonObj = JObject.Parse(result);
        //    jsonObjDate = jsonObj;
        //    if (jsonObj["return_code"].ToString().Equals("SUCCESS"))
        //    {
        //        if (trade_type == "ALIPAYF2F")
        //        {
        //            resulta = jsonObj["code_url"].ToString();
        //        }
        //        if (trade_type == "ALIPAYPC")
        //        {
        //            resulta = jsonObj["pay_url"].ToString();
        //        }

        //        if (ordermodel.ChannelCode == "ALIPAYWAP")
        //        {
        //            resulta = jsonObj["pay_url"].ToString();
        //            //Orders.SetOrderToGateid(172, ordermodel.OrderID);
        //            HttpContext.Current.Response.Redirect(resulta);
        //            HttpContext.Current.Response.End();
        //        }
        //        if (trade_type == "WECHATNATIVE")
        //        {
        //            resulta = jsonObj["code_url"].ToString();
        //            return ("MakeQRCode.aspx?data=" + HttpUtility.UrlEncode(resulta));
        //        }

        //        return "http://mobile.qq.com/qrcode?url=" + resulta;
        //    }
        //    else
        //    {
        //        return ("no");
        //    }

        //}
    }
}

