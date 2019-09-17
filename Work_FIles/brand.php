<? include "$_SERVER[DOCUMENT_ROOT]/child/inc/head.php"; // head 파일 추가 ?>
<? include "$_SERVER[DOCUMENT_ROOT]/adm/module/connect.php"; // 로그분석 ?>
<? include "$_SERVER[DOCUMENT_ROOT]/child/inc/header.php"; // header ?>
<?
include $_SERVER['DOCUMENT_ROOT']."/model/user.php";
include $_SERVER['DOCUMENT_ROOT']."/model/brand_favorite.php";
try{
  $userModel = new UserModel();
  $favoriteModel = new BrandFavoriteModel();

  $users = $userModel->getMembers(
    array(
      "type" => "brand",
      "addinfo2" => $addinfo2
    )
  );

  foreach ($users['data'] as &$user) {
      $favorite = $favoriteModel->getFavorite(
          array(
              "brand_id" => $user['id'],
              "user_id" => $wiz_session['id']
          )
      );
      $user['favorite'] = $favorite ? true : false;
  }
}
catch(Exception $e){
  error($e->getMessage());
}
?>
<div id="popup">
    <? include "$_SERVER[DOCUMENT_ROOT]/adm/module/popup.php"; // 팝업관리 ?>
</div>

<div id="wrap">
    <div class="sub">
        <div class="container">
            <!--div class="lnb_wrap">
                <p class="title-xsmall8"><strong>BRAND</strong></p>
                <ul class="lnb_menu">
                    <li><a href="">ALL<a></li>
                    <li><a href="">WOMEN<a></li>
                    <li><a href="">MEN<a></li>
                    <li><a href="">CHILD<a></li>
                    <li><a href="">ETC<a></li>
                    <li>-</li>
                    <li><a href="">BEST BRAND<a></li>
                </ul>
                <div id="brand-search">
                    <//? include "$_SERVER[DOCUMENT_ROOT]/adm/module/prd_search.php"; // 상품검색 ?>
                </div>
            </div-->
            <!--div class="sub-nav">
                <ul>
                  <li><a href="/index.php"><i></i></a></li>
                  <li><a href="/child/sub/1_intro/intro.php"><span>브랜드 소개</span></a></li>
                </ul>
            </div-->
            <div class="store-contents">
                <? if($ptype != "view"){ ?>
                <div class="s-left">
                    <div id="menu-container">
                        <ul class="menu-list2 __menu-list-store">
                            <li class="toggle accordion-toggle active-tab fixed-tab">
                                <span class="icon-plus"></span>
                                <a class="menu-link">BRAND</a>
                            </li>
                            <ul class="menu-submenu accordion-content open" style="display:block;">
                                <li<?if($addinfo2=="여성의류"){?> class="on"<?}?>><a href="/child/sub/shop/brand.php?addinfo2=여성의류">여성의류</a></li>
                                <li<?if($addinfo2=="남성의류"){?> class="on"<?}?>><a href="/child/sub/shop/brand.php?addinfo2=남성의류">남성의류</a></li>
                                <li<?if($addinfo2=="아동의류"){?> class="on"<?}?>><a href="/child/sub/shop/brand.php?addinfo2=아동의류">아동의류</a></li>
                                <li<?if($addinfo2=="기타"){?> class="on"<?}?>><a href="/child/sub/shop/brand.php?addinfo2=기타">기타</a></li>
                            </ul>
                        </ul>
                    </div>

                    <div class="brand-search-area">
                        <div class="brand-search">
                            <input type="text" name="" value="">
                            <button class="button" type="button"></button>
                        </div>
                    </div>
                </div><?}?><div<? if($ptype != "view"){ ?> class="s-right"<?}?>>
                    <div class="store-top">
                        <div class="container">

                            <!-- 상단 등록정보  -->

                            <table width="100%" border="0" cellpadding="0" cellspacing="0" class="list-table"><tbody><tr><td><form action="/child/sub/shop/product.php" method="get">
                                <input type="hidden" name="catcode" value="100000000">
                                <input type="hidden" name="grp" value="">
                                <input type="hidden" name="brand" value=""> <table id="list_count_style" width="100%" border="0" cellspacing="0" cellpadding="0"><tbody><tr><td width="100%">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tbody>
                                            <tr>
                                                <td align="left" height="30" class="list_count">총 <strong class="count_text">{{ users.summary.total_count }}</strong>개 의 브랜드가 등록 되었습니다.</td> <td align="right">
                                                    <table border="0" cellpadding="0" cellspacing="0">
                                                        <tbody>
                                                            <tr>
                                                            <td></td>
                                                            <td><select name="orderby" onchange="this.form.submit();">
                                                                <option value="">정렬방식</option> <option value="viewcnt desc">가나다 순</option>
                                        <option value="prdcode desc">최근등록순 순</option> <option value="sellprice asc">최저가격 순</option>
                                        <option value="sellprice desc">최고가격 순</option></select></td></tr></tbody></table></td></tr>
                                    </tbody></table></td></tr></tbody></table></form></td></tr></tbody>
                                </table>

                                <!-- 브랜드 리스트  -->
                            <ul class="grid brand-list-ul">

                                <li v-for="user in users.data" class="grid-5">
                                    <div class="_brd-box">
                                        <div class="b-list-thumbnail">
                                            <a v-bind:href="'/child/sub/shop/brand_view.php?id='+user.id">
                                                <img v-bind:src="'/adm/data/member/' + user.photo">
                                            </a>
                                        </div>
                                        <div class="b-list-info">
                                            <span class="b-list-eng title-xsmall5 color-grey7 weight-light">
                                                <a>{{ user.addinfo5.split("|")[0] }}</a>
                                            </span>
                                            <span class="b-list-name title-xsmall3 color-gray2 weight-medium"><strongv>{{ user.name }}</strong></span>
                                            <button type="button" name="button" class="list-like" v-bind:class="{'list-like-on': user.favorite}" v-on:click="onFavorite(user)"></button>
                                        </div>
                                    </div>
                                </li>

                            </ul>

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
$(".store-visual-slide")
.on('beforeChange',function(event, slick, currentSlide, nextSlide){
    $('.s-v-btn').attr('aria-pressed', 'false');
    $('.store-v-button ul li').eq(nextSlide).children('.s-v-btn').attr('aria-pressed', 'true');
})
.slick({
    fade: true,
    draggable: false,
    cssEase: 'ease-out',
    arrows: false,
    dots: true,
    infinite: true,
    autoplay: true,
    autoplaySpeed: 3500,
    speed : 1500,
    slidesToShow: 1,
    slidesToScroll: 1,
    pauseOnFocus: false,
    pauseOnHover: false
});
$('.s-v-btn').click(function(){
    $('.store-visual-slide').slick('slickGoTo', $(this).parents('li').index(), false);
});


$(".s-top-slide").slick({
    fade: true,
    draggable: true,
    cssEase: 'ease-out',
    arrows: true,
    dots: true,
    infinite: true,
    autoplay: true,
    autoplaySpeed: 3500,
    speed : 1000,
    slidesToShow: 1,
    slidesToScroll: 1,
    pauseOnFocus: false,
    pauseOnHover: false
});
</script>

<script>
Vue.data.users = <?=json_encode($users)?>;
Vue.methods.onFavorite = function(user){
  (!user.favorite) ? this.postFavorite(user) : this.deleteFavorite(user);
}

Vue.methods.postFavorite = function(user) {
    $.ajax({
      url: "/child/sub/shop/ajax.php",
      method: "post",
      data: {
        _controller: "favorite",
        _method: "post",
        brand_id: user.id
      },
      dataType: "json",
      success: function(res){
        if(res.error) alert(res.error);
        else{
          user.favorite = true;
        }
      }
    });
}

Vue.methods.deleteFavorite = function(user) {
    $.ajax({
      url: "/child/sub/shop/ajax.php",
      method: "post",
      data: {
        _controller: "favorite",
        _method: "delete",
        brand_id: user.id
      },
      dataType: "json",
      success: function(res){
        if(res.error) alert(res.error);
        else{
          user.favorite = false;
        }
      }
    });
}
</script>


<? include "$_SERVER[DOCUMENT_ROOT]/child/inc/footer.php"; ?>
