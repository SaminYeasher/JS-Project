<?p
add_shortcode( 'asearch', 'asearch_func' );
function asearch_func( $atts ) {
    $atts = shortcode_atts( array(
        'source' => 'page,post,product',
        'image' => 'true'
    ), $atts, 'asearch' );
static $asearch_first_call = 1;
$source = $atts["source"];
$image = $atts["image"];
$sForam = '<div class="search_bar">
    <form class="asearch" id="asearch'.$asearch_first_call.'" action="/" method="get" autocomplete="off">
        <input type="text" name="s" placeholder="Search ..." id="keyword" class="input_search" onkeyup="searchFetch(this)"><button id="mybtn">🔍</button>
    </form><div class="search_result" id="datafetch" style="display: none;">
        <ul>
            <li>Please wait..</li>
        </ul>
    </div></div>';
$java = '<script>
function searchFetch(e) {
var datafetch = e.parentElement.nextSibling
if (e.value.trim().length > 0) { datafetch.style.display = "block"; } else { datafetch.style.display = "none"; }
const searchForm = e.parentElement;	
e.nextSibling.value = "Please wait..."
var formdata'.$asearch_first_call.' = new FormData(searchForm);
formdata'.$asearch_first_call.'.append("source", "'.$source.'") 
formdata'.$asearch_first_call.'.append("image", "'.$image.'") 
formdata'.$asearch_first_call.'.append("action", "asearch") 
AjaxAsearch(formdata'.$asearch_first_call.',e) 
}
async function AjaxAsearch(formdata,e) {
  const url = "'.admin_url("admin-ajax.php").'?action=asearch";
  const response = await fetch(url, {
      method: "POST",
      body: formdata,
  });
  const data = await response.text();
if (data){	e.parentElement.nextSibling.innerHTML = data}else  {
e.parentElement.nextSibling.innerHTML = `<ul><a href="#"><li>Sorry, nothing found</li></a></ul>`
}}	
document.addEventListener("click", function(e) { if (document.activeElement.classList.contains("input_search") == false ) { [...document.querySelectorAll("div.search_result")].forEach(e => e.style.display = "none") } else {if  (e.target.value.trim().length > 0) { e.target.parentElement.nextSibling.style.display = "block"}} })
</script>';
$css = '<style>form.asearch {display: flex;flex-wrap: nowrap;border: 1px solid #d6d6d6;border-radius: 5px;padding: 3px 5px;}
form.asearch button#mybtn {padding: 5px;cursor: pointer;background: none;}
form.asearch input#keyword {border: none;}
div#datafetch {
    background: white;
    z-index: 10;
    position: absolute;
    max-height: 425px;
    overflow: auto;
    box-shadow: 0px 15px 15px #00000036;
    right: 0;
    left: 0;
    top: 50px;
}
div.search_bar {
    width: 600px!important;
    max-width: 90%!important;
    position: relative;
}

div.search_result ul a li {
    margin: 0px;
    padding: 5px 0px;
    padding-inline-start: 18px;
    color: #3f3f3f;
    font-weight: bold;
}
div.search_result li {
    margin-inline-start: 20px;
}
div.search_result ul {
    padding: 13px 0px 0px 0px;
    list-style: none;
    margin: auto;
}

div.search_result ul a {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 5px;
}
div.search_result ul a:hover {
    background-color: #f3f3f3;
}
.asearch input#keyword {
    width: 100%;
}
</style>';
if ( $asearch_first_call == 1 ){	
	 $asearch_first_call++;
	 return "{$sForam}{$java}{$css}"; 
    } elseif  ( $asearch_first_call > 1 ) {
		$asearch_first_call++;
		return "{$sForam}"; }}

add_action('wp_ajax_asearch' , 'asearch');
add_action('wp_ajax_nopriv_asearch','asearch');
function asearch(){
    $the_query = new WP_Query( array( 'posts_per_page' => 10, 's' => esc_attr( $_POST['s'] ), 'post_type' =>  explode(",", esc_attr( $_POST['source'] )))  );
    if( $the_query->have_posts() ) :
        echo '<ul>';
        while( $the_query->have_posts() ): $the_query->the_post(); ?>
            <a href="<?php echo esc_url( post_permalink() ); ?>"><li><?php the_title();?></li>
<?php $image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'single-post-thumbnail' );?>                               
<?php if ( $image[0] && trim(esc_attr( $_POST['image'] )) == "true" ) {  ?>  <img src="<?php the_post_thumbnail_url('thumbnail'); ?>" style="height: 60px;padding: 0px 5px;"> <?php }  ?> </a>
        <?php endwhile;
       echo '</ul>';
        wp_reset_postdata();  
    endif;
    die();
}
?>