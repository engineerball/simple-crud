function check_idcard(idcard){
  if(idcard.value == ""){ return false;}
  if(idcard.length < 13){ return false;}

  var num = str_split(idcard); // function เพิ่มเติม
  var sum = 0;
  var total = 0;
  var digi = 13;

  for(i=0;i<12;i++){
    sum = sum + (num[i] * digi);
    digi--;
  }
  total = ((11 - (sum % 11)) % 10);

  if(total == num[12]){ //	alert('รหัสหมายเลขประจำตัวประชาชนถูกต้อง');
  return true;
  }else{ //	alert('รหัสหมายเลขประจำตัวประชาชนไม่ถูกต้อง');
    return false;
  }
}


function str_split ( f_string, f_split_length){
  f_string += '';
  if (f_split_length == undefined) {
    f_split_length = 1;
  }
  if(f_split_length > 0){
    var result = [];
    while(f_string.length > f_split_length) {
      result[result.length] = f_string.substring(0, f_split_length);
      f_string = f_string.substring(f_split_length);
    }
    result[result.length] = f_string;
    return result;
  }
  return false;
}

function id_card(id){
  if(check_idcard(id.value)){
    alert("ID Card Completed.");
  }else{
    alert("ID Card Error ?\nPlease Tye Again");
    id.value = "";
    id.focus();
  }
}
