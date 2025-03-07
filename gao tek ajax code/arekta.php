<?php
add_shortcode('asearch', 'asearch_func');
function asearch_func($atts) {
    $atts = shortcode_atts(array(
        'source' => 'product',
        'image' => 'true'
    ), $atts, 'asearch');
    static $asearch_first_call = 1;
    $source = esc_attr($atts['source']);
    $image = esc_attr($atts['image']);
    $searchForm = '<div class="search_bar">
        <form class="asearch" id="asearch' . $asearch_first_call . '" action="/" method="get" autocomplete="off">
        <input type="text" name="s" placeholder="Search..." id="keyword" class="input_search" onkeyup="searchFetch(this)">
       <button id="mybtn" type="button" onclick="searchFetch(this)">🔍</button>
        <select id="category-dropdown" class="category-dropdown" onchange="searchFetch(this)">
        <option value="all">All categories</option>
        <option value="posters">Posters</option>
        <option value="hoodies">Hoodies</option>
        <option value="t-shirts">T-shirts</option>
        <option value="clothing">Clothing</option>
        <option value="music">Music</option>
        <option value="singles">Singles</option>
        <option value="albums">Albums</option>
        </select>
        </form>
        <div class="search_result" id="datafetch" style="display: none;">
        <ul>
        <li>Please wait..</li>
        </ul>
        </div>
        </div>';
}