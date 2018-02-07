/**
 * 
 * @authors ShenYan (52o@qq52o.cn)
 * @date    2018-02-06 11:09:44
 * @version $Id$
 */
 wx.request({
      url: 'https://yourhost.com/wxpay/payfee.php',//改成你自己的链接
      data:{
        id: app.globalData.openid,//获取用户 openid
        fee:100 //商品价格
      },
      header: {
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      method: 'POST',
      success: function (res) {
        console.log(res.data);
        console.log('调起支付');
        wx.requestPayment({
          'timeStamp': res.data.timeStamp,
          'nonceStr': res.data.nonceStr,
          'package': res.data.package,
          'signType': 'MD5',
          'paySign': res.data.paySign,
          'success': function (res) {
            console.log('success');
            wx.showToast({
              title: '支付成功',
              icon: 'success',
              duration: 3000
            });
          },
          'fail': function (res) {
            console.log(res);
          },
          'complete': function (res) {
            console.log('complete');
          }
        });
      },
      fail: function (res) {
        console.log(res.data)
      }
    });
