using JRAPI.Common;
using JRAPI.Data;
using JRAPI.Interfaces;
using JRAPI.Model;
using System;
using System.Collections.Generic;
using System.Security.Cryptography;
using System.Text;
using System.Web.UI;
namespace JRAPI.Pay.Services.txqpc
{
    public partial class callback : Page
    {
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

            string signInfo = str.Replace("-", "").ToUpper();
            return signInfo.Equals(sign.ToUpper());

        }

        protected void Page_Load(object sender, EventArgs e)
        {
            NetGateModel gate = new NetGateModel();
            string s = "";
            string str2 = gate.GateUserID;
            string str3 = JRRequest.GetFormString("total_fee");
            string billNO = JRRequest.GetFormString("out_trade_no");
           // string str5 = Request["result_code");
            string return_code = JRRequest.GetFormString("return_code");
            string return_msg = JRRequest.GetFormString("return_msg");
            string result_code = JRRequest.GetFormString("result_code");
            string trade_type = JRRequest.GetFormString("trade_type");
            string time_end = JRRequest.GetFormString("time_end");
            string attach = JRRequest.GetFormString("attach");
            string transaction_id = JRRequest.GetFormString("transaction_id");
            string sign = JRRequest.GetFormString("sign");
            string err_code = JRRequest.GetFormString("err_code");
            string err_code_des = JRRequest.GetFormString("err_code_des");

            SortedDictionary<string, string> dict2 = new SortedDictionary<string, string>();
            dict2.Clear();
            dict2.Add("result_code", result_code);
            dict2.Add("return_msg", return_msg);
            dict2.Add("return_code", return_code);
            dict2.Add("err_code", err_code);
            dict2.Add("err_code_des", err_code_des);
            dict2.Add("trade_type", trade_type);
            dict2.Add("out_trade_no", billNO);
            dict2.Add("transaction_id", transaction_id);
            dict2.Add("attach", attach);
            dict2.Add("time_end", time_end);
            dict2.Add("total_fee", str3);
            dict2.Add("sign", sign);
            string strrr = string.Empty;
            foreach (string key2 in dict2.Keys)
            {
                strrr += key2 + ":" + dict2[key2] + "&";
            }
            Log.Info("固定码", Request.Url.ToString() + "?" + strrr);

            if (ChekSign(dict2["sign"], dict2, gate.GateUserKey))
            {
                OrdersModel order = Orders.GetModel(billNO);
                if (order != null)
                {
                    NetGateModel model = NetGate.GetModel(order.Gateid);
                    if (model != null)
                    {
                        int num = 2;
                        if (dict2["result_code"] == "SUCCESS")
                        {
                            num = 1;
                            order.Realmoney = order.OrderMoney;//  decimal.Parse(str3);
                            order.OrderStatus = num;
                            order.SuperNO = transaction_id;
                            order.GateMsg = "|||";
                            Orders.UpdateOrderStatus(order);
                            s = "SUCCESS";
                        }
                        else
                        {
                            s = "result_code == FILL";
                        }
                    }
                    else
                    {
                        s = "没有找到网关";
                    }
                }
                else
                {
                    s = "没有找到订单";
                }
            }
            base.Response.Write(s);
            base.Response.End();
        }
    }
}
