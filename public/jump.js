function jump() {
  var sid = $("#sid")
    .val()
    .toUpperCase();

  if (sid.length === 9) {
    sid = toSBC(sid);

    if (isalpha(sid.slice(0, 3)) && isdigit(sid.slice(3))) {
      $.ajax({
        type: "post",
        datatype: "json",
        url: "../routes/tpAPI.php",
        data: {
          sid: sid
        },
        success: function(data) {
          if (data.data !== 1 && data.data !== 0) {
            console.log(data.data);
            // document.location.href = data.data;
          } else if (data.data === 0) {
            alert("查無此學號，請重新輸入");
          } else {
            alert("發生錯誤");
          }
        }
      });
    } else {
      alert(sid + "學號輸入錯誤，請輸入正確學號");
    }
  } else {
    alert("學號長度錯誤，請輸入正確學號");
  }
}

function isalpha(c) {
  return (c >= "a" && c <= "z") || (c >= "A" && c <= "Z");
}

function isdigit(c) {
  return c >= "0" && c <= "9";
}

function toSBC(str) {
  var result = "";
  var len = str.length;
  for (var i = 0; i < len; i++) {
    var cCode = str.charCodeAt(i);
    //全形與半形相差（除空格外）：65248（十進位制）
    cCode = cCode >= 0xff01 && cCode <= 0xff5e ? cCode - 65248 : cCode;
    //處理空格
    cCode = cCode == 0x03000 ? 0x0020 : cCode;
    result += String.fromCharCode(cCode);
  }
  return result;
}
