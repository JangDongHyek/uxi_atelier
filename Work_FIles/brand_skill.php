<?php
class BrandSkillModel{

    function escape($_param){
        $param = array();
        foreach($_param as $key => $value){
          $param[$key] = mysql_real_escape_string($value);
        }
        return $param;
    }

  function postBrandSkill($_param){

    $param = $this->escape($_param);

    $_id = uniqid().str_pad(rand(0, 99), 2, "0", STR_PAD_LEFT);

    $sql = sprintf(
        "insert into uxi_brand_skills (
            _id,
            brand_id,
            upfile1, upfile2, upfile3, upfile4, upfile5,
            content1, content2, content3, content4, content5,
            youtube_link,
            source
        )
        values (
            '%s',
            '%s',
            '%s','%s','%s','%s','%s',
            '%s','%s','%s','%s','%s',
            '%s',
            '%s'
        )",
        $_id,
        $param['brand_id'],
        $param['upfile1'],$param['upfile2'],$param['upfile3'],$param['upfile4'],$param['upfile5'],
        $param['content1'],$param['content2'],$param['content3'],$param['content4'],$param['content5'],
        $param['youtube_link'],
        $param['source']
      );

      $result = mysql_query($sql);
      if(!$result) throw new Exception(mysql_error());

      return $_id;

    }

    function putBrandSkill($_param) {

        $param = $this->escape($_param);


        $sql = sprintf(
            "update uxi_brand_skills set
                upfile1 = '%s', upfile2 = '%s', upfile3 = '%s', upfile4 = '%s', upfile5 = '%s',
                content1 = '%s', content2 = '%s', content3 = '%s', content4 = '%s', content5 = '%s',
                youtube_link = '%s',
                source = '%s'
            where brand_id = '%s'",
            $param['upfile1'], $param['upfile2'], $param['upfile3'], $param['upfile4'], $param['upfile5'],
            $param['content1'], $param['content2'], $param['content3'], $param['content4'], $param['content5'],
            $param['youtube_link'],
            $param['source'],
            $param['brand_id']
        );

        $result = mysql_query($sql);
        if(!$result) throw new Exception(mysql_error());

        return $param['_id'];
    }

    function getBrandSkill($_param) {

        $param = $this->escape($_param);
        $brand_id = $param['brand_id'];
        $sql = "
            select
                _id,
                brand_id ,
                content1,content2,content3,content4,content5,
                upfile1,upfile2,upfile3,upfile4,upfile5,
                youtube_link,
                source
            from
                uxi_brand_skills
            where brand_id = '{$brand_id}' ";

        $result = mysql_query($sql);

        if(!$result) throw new Exception(mysql_error());

        if($row = mysql_fetch_array($result, MYSQL_ASSOC)){
          return $row;
        }
        else return array();
    }

}
?>
