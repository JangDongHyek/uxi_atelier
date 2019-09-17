<?php
class BrandFavoriteModel {

    function postFavorite($_param) {

        $sql = sprintf(
            "
            insert into uxi_brand_favorite (
                user_id,
                brand_id
                )
            values (
                '%s',
                '%s'
                )
            ",
            $_param['user_id'],
            $_param['brand_id']
        );

        $result = mysql_query($sql);
        if(!$result) throw new Exception(mysql_error());
    }

    function deleteFavorite($_param) {

        $sql = sprintf(
            "
            delete
                from uxi_brand_favorite
            where
                user_id = '%s'
            and
                brand_id = '%s'
            " ,
            $_param['user_id'],
            $_param['brand_id']
        );

        $result = mysql_query($sql);
        if(!$result) throw new Exception(mysql_error());
    }

    function getFavorite($_param) {

        $user_id = $_param['user_id'];
        $brand_id = $_param['brand_id'];

        $sql = "
            select
                *
            from
                uxi_brand_favorite
            where
                user_id = '{$user_id}'
            and
                brand_id = '{$brand_id}'
        ";

        $result = mysql_query($sql);

        if(!$result) throw new Exception(mysql_error());

        if($row = mysql_fetch_array($result, MYSQL_ASSOC)){
          return $row;
        }

    }
}
 ?>
