<?php
class SettleModel{

  function escape($_param){
    $param = array();
    foreach($_param as $key => $value){
      $param[$key] = mysql_real_escape_string($value);
    }
    return $param;
  }

  function getSettlements($_param){

    $param = $this->escape($_param);

    // 검색조건
    $search_sql = "";
    if($param['user_id']) $search_sql .= " and brand_id = '{$param['user_id']}'";

    // 정렬조건
    $order_sql = " wdate desc ";

    // summary
    $sql = "select idx from uxi_settlement where 1 $search_sql";
    $result = mysql_query($sql);

    if (!$result) throw new Exception(mysql_error());
    $total_count = mysql_num_rows($result);

    // 데이터
    $sql = "select * from uxi_settlement where 1 $search_sql order by $order_sql ";
    $result = mysql_query($sql);
    if(!$result) throw new Exception(mysql_error());

    $data = array();
    while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
        array_push($data,$row);
    }

    return array(
        "summary" => array("total_count" => $total_count),
        "data" => $data
    );

  }

  function postSettlement($_param){

    $param = $this->escape($_param);

    // 인덱스
    $_id = uniqid().str_pad(rand(0, 99), 2, "0", STR_PAD_LEFT);


    $sql = sprintf(
        "
        insert into uxi_settlement (
            _id,
            year, month,
            brand_id,
            price, amount, payment, commission,
            details,
            wdate,mdate
        )
        values (
            '%s',
            '%s', '%s',
            '%s',
            '%s', '%s', '%s', '%s',
            '%s',
            now(), now()
        )",
        $_id,
        $param['year'], $param['month'],
        $param['user_id'],
        $param['price'], $param['amount'], $param['payment'], $param['commission'],
        $param['details']
    );

    $result = mysql_query($sql);
    if(!$result) throw new Exception(mysql_error());

    return $_id;

  }

  function putSettlement($_param){

    $param = $this->escape($_param);

    // 업데이트 필드
    $update_sql = "_id = _id";
    if($param['year']) $update_sql .= ", year = '{$param['year']}'";
    if($param['month']) $update_sql .= ", month = '{$param['month']}'";
    // if($param['brand_id']) $update_sql .= ", brand_id = '{$param['brand_id']}'";
    if($param['price']) $update_sql .= ", price = '{$param['price']}'";
    if($param['amount']) $update_sql .= ", amount = '{$param['amount']}'";
    if($param['payment']) $update_sql .= ", payment = '{$param['payment']}'";
    if($param['commission']) $update_sql .= ", commission = '{$param['commission']}'";
    if($param['details']) $update_sql .= ", details = '{$param['details']}'";
    $update_sql .= ", mdate = now()";

    $sql = "update uxi_settlement set $update_sql where _id = '{$param['_id']}'";
    $result = mysql_query($sql);
    if(!$result) throw new Exception(mysql_query($sql));

    return $param['_id'];

  }
}
?>
