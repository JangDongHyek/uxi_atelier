<? include "$_SERVER[DOCUMENT_ROOT]/child/inc/head.php"; // head 파일 추가 ?>
<? include "$_SERVER[DOCUMENT_ROOT]/adm/module/connect.php"; // 로그분석 ?>
<? include "$_SERVER[DOCUMENT_ROOT]/child/inc/header.php"; // header ?>
<?
include $_SERVER['DOCUMENT_ROOT']."/model/user.php";
include $_SERVER['DOCUMENT_ROOT']."/model/product.php";
include $_SERVER['DOCUMENT_ROOT']."/model/brand_skill.php";
include $_SERVER['DOCUMENT_ROOT']."/model/wishlist.php";
include $_SERVER['DOCUMENT_ROOT']."/model/brand_favorite.php";

try{
  $userModel = new UserModel();
  $productModel = new ProductModel();
  $brandSkillModel = new BrandSkillModel();
  $wishlistModel = new WishlistModel();
  $favoriteModel = new BrandFavoriteModel();

  // 브랜드 정보
  $user = $userModel->getMember(
    array("id" => $id)
  );

  // 브랜드의 상품목록
  $products = $productModel->getProducts(
    array("brand_id" => $id)
  );

  // 상품 찜하기 내역
  foreach($products['data'] as &$product){
    $wishlist = $wishlistModel->getWishlist(
      array(
        "user_id" => $wiz_session['id'],
        "prdcode" => $product['prdcode']
      )
    );
    $product['wish'] = $wishlist['summary']['total_count'] ? true : false;
  }

  // 브랜드 기술력
  $skill = $brandSkillModel->getBrandSkill(
    array("brand_id" => $id)
  );

  $favorite = $favoriteModel->getFavorite(
      array(
          "user_id" => $wiz_session['id'],
          "brand_id" => $user['id']
      )
  );
  $favorite['check'] = $favorite ? true : false;
  $favorite['brand'] = $user['id'];
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
            <div class="brand-contents">
                <div class="brand-top">
                    <div class="">
                        <div class="s-top-wrap">
                            <div id="brand_view_wrap">
                                <div class="view-top">
                                	<div class="">
                                		<div id="brand_view_image">
                                			<div id="View_brand_Img">
                                				<img v-bind:src="'/adm/data/member/'+user.photo" name="brdimg">
                                			</div>
                                		</div>
                                		<div id="brand_view_info">
                                			<form name="brdForm" id="brdForm">
                                				<table width="100%" border="0" cellpadding="0" cellspacing="0">
                                					<tbody>
                                						<tr>
                                							<td class="b_name">
                                								<h1 class="b_title title-regular5 weight-light color-dark3">{{ user.addinfo5.split("|")[0] }}<span class="b_title_kor title-small b">{{ user.name }}</span></h1>
                                                                <span class="brd-hashtag pr-small weight-light color-grey9">{{ user.addinfo5.split("|")[2] }}</span>
                                                                <button type="button" name="button" class="like" v-bind:class="{'like-on' : favorite.check}" v-on:click="onFavorite(favorite)"></button>
                                							</td>
                                						</tr>
                                						<tr>
                                							<td class="brd-info-td">
                    											<div class="b_info pr weight-light color-grey7">
                                                                    <p v-html="user.intro.replace(/\n/g,'<br>')"></p>
                                                                </div>
                                							</td>
                                						</tr>
                                						<tr>
                                							<td colspan="2">
                                								<div class="view-btn-wrap">
                                									<div>
                                										<button type="button" class="btn btn_point ">문의하기</button>
                                									</div>
                                								</div>
                                							</td>
                                						</tr>
                                					</tbody>
                                				</table>
                                			</form>
                                		</div>
                                	</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="view-bottom">
                    <div class="">

                        <div class="brd_Detail_Wrap">
                            <a name="info"></a>
                            <ul>
                                <li class="title-xsmall6" v-bind:class="{ 'current': (tab == 'product') }">
                                    <a v-on:click="tab = 'product'"><p>상품리스트</p></a>
                                </li>
                                <li class="title-xsmall6" v-bind:class="{ 'current': (tab == 'skill')}">
                                    <a v-on:click="tab = 'skill'"><p>브랜드 기술력</p></a>
                                </li>
                            </ul>
                        </div>

                        <div v-if="tab == 'product'" class="view-prd">
                            <div data-animate="fade">
                              <div class="product-list-area">
                                <div class="container-30">
                                  <ul class="grid product-list-ul">
                                    <li v-for="product in products.data" class="grid-5">
                                      <div class="_prd-box">
                                        <div class="p-list-thumbnail">
                                          <a v-bind:href="'/child/sub/shop/product.php?ptype=view&prdcode=' + product.prdcode">
                                            <img v-bind:src="product.prdimg">
                                          </a>
                                        </div>
                                        <div class="p-list-info">
                                          <span class="p-list-name title-xsmall7 color-grey7 weight-light">
                                            <a v-bind:href="'/child/sub/shop/product.php?ptype=view&prdcode=' + product.prdcode">{{ product.prdcom }}</a>
                                          </span>
                                          <span class="p-list-eng title-xsmall3 color-dark2">
                                            <strong>{{ product.prdname }}</strong>
                                          </span>
                                          <span class="p-list-price title-xsmall7 color-dark3 weight-light">
                                            <span>{{ parseInt(product.sellprice).format() }}</span>
                                            <span>원</span>
                                          </span>
                                          <button type="button" name="button" v-on:click="onWishlist(product)" class="list-like" v-bind:class="{'list-like-on': product.wish}"></button>
                                        </div>
                                      </div>
                                    </li>
                                  </ul>
                                </div>
                              </div>
                            </div>
                        </div>

                        <div v-if="tab == 'skill'" class="view-box">
                            <div class="brand-media" v-html="skill.source"></div>
                            <ul class="brand-row-ul">
                                <li v-for="index in skill_count">
                                    <div class="brand-photo">
                                        <img v-bind:src="'/adm/data/brand_skill/'+skill['upfile'+index]" alt="">
                                    </div><div class="brand-context">
                                        <p class="pr-small color-grey5 weight-light" v-html="skill['content'+index].replace(/\n/g, '<br>')"></p>
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
Vue.data.tab = "product"; // tab:  ["product", "skill"]
Vue.data.user = <?=json_encode($user)?>;
Vue.data.products = <?=json_encode($products)?>;
Vue.data.skill = <?=json_encode($skill)?>;
Vue.data.favorite = <?=json_encode($favorite)?>;
// 브랜드 기술력 루프갯수
Vue.computed.skill_count = function(){
  var count = 0;
  for(var i=1; i<=5; i++){
    if(this.skill['upfile'+i] && this.skill['content'+i]) count++;
  }
  return count;
};

Vue.methods.onWishlist = function(product){
  (!product.wish) ? this.postWishlist(product) : this.deleteWishlist(product);
}
Vue.methods.postWishlist = function(product){
  var app = this;
  $.ajax({
    url: "/child/sub/shop/ajax.php",
    method: "post",
    data: {
      _controller: "wishlist",
      _method: "post",
      prdcode: product.prdcode
    },
    dataType: "json",
    success: function(res){
      if(res.error) alert(res.error);
      else{
        product.wish = true;
      }
    }
  });
}
Vue.methods.deleteWishlist = function(product){
  var app = this;
  $.ajax({
    url: "/child/sub/shop/ajax.php",
    method: "post",
    data: {
      _controller: "wishlist",
      _method: "delete",
      prdcode: product.prdcode
    },
    dataType: "json",
    success: function(res){
      if(res.error) alert(res.error);
      else{
        product.wish = false;
      }
    }
  });
}

Vue.methods.onFavorite = function(favorite) {
    (!favorite.check) ? this.postFavorite(favorite) : this.deleteFavorite(favorite);
}

Vue.methods.postFavorite = function(favorite) {
    $.ajax({
        url: "/child/sub/shop/ajax.php",
        method : "post",
        data: {
            _controller : "favorite",
            _method : "post",
            brand_id : favorite.brand,
        },
        dataType: "json",
        success: function(res){
          if(res.error) alert(res.error);
          else{
            favorite.check = true;
          }
        }
      });
}

Vue.methods.deleteFavorite = function(favorite) {
    $.ajax({
      url: "/child/sub/shop/ajax.php",
      method: "post",
      data: {
        _controller: "favorite",
        _method: "delete",
        brand_id: favorite.brand
      },
      dataType: "json",
      success: function(res){
        if(res.error) alert(res.error);
        else{
          favorite.check = false;
        }
      }
    });
}
</script>


<? include "$_SERVER[DOCUMENT_ROOT]/child/inc/footer.php"; ?>
