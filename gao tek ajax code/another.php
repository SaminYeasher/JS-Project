<?php
add_action('wp_enqueue_scripts', 'wp_parent_styles');
function enqueue_parent_styles(){
    wp_enqueue_style('parent-style', get_template_directory_uri().'/style.css');

}
add_shortcode('asearch', 'asearch_func');
function asearch_func($atts) {
    $atts = shortcode_atts(array(
        'source' => 'product',
        'image' => 'true'
        ), $atts,'asearch');
    static $asearch_first_call = 1 ;
    $source= $atts ["source"];
    $image = $atts ["image"];
    // Fetch product categories to display in the dropdown
    $categories = get_terms(array(
        'taxonomy' => 'product_cat',
		'hide_empty' => true,
    ));

    // Search form HTML
    $sForam = '<div class="search_bar">
        <form class="asearch" id="asearch'.$asearch_first_call.'" action="/" method="get" autocomplete="off">
            <input type="text" name="s" placeholder="Search products..." id="keyword" class="input_search" onkeyup="searchFetch(this)">
            <button id="mybtn">🔍</button>
            <select name="category" id="category">
                <option value="">All Categories</option>';
                    foreach ($categories as $category) {
                        $sForam .= '<option value="' . $category->slug . '">'. $category->name . '</option>';
                    }
    $sForam .= '</select>
        </form>
        <div class="search_result" id="datafetch" style="display: none;">
            <ul><li>Please wait..</li></ul>
        </div>
    </div>';

    // JavaScript and CSS

    $java = '<script>
        function searchFetch(e) {
        var datafetch e.parentElement.nextElementSibling;
        var searchInput = e;
        var categoryInput = document.getElementById("category");

        if (searchInput.value.trim().length > 0) {
            datafetch.style.display = "block";
        } else {
            datafetch.style.display = "none";
        }

        const searchForm = searchInput.parentElement;
        searchInput.nextElementSibling.value = "Please wait...";

        var formdata = new FormData(searchForm);
        formdata.append("source", "product");
        formdata.append("image", "true");
        formdata.append("category", categoryInput.value);
        formdata.append("action", "asearch");

        AjaxAsearch (formdata, searchInput);
    }
    
    async function AjaxAsearch(formdata, e) {
        const url = "' . admin_url("admin-ajax.php") . '";
        const response = await fetch(url, {
            method: "POST",
            body: formdata
        });
        const data = await response.text();
        var datafetch = e.parentElement.nextElementSibling;

        if (data) {
            datafetch.innerHTML = data;
        } else {
            datafetch.innerHTML = `<ul><a href="#"><li>Sorry, nothing found</li></a></ul>`;
        }
    }
    
    document.addEventListener("click", function(e) {
        if (!document.activeElement.classList.contains("input_search")) {
            [...document.querySelectorAll("div.search_result")).forEach(e => e.style.display = "none");
        } else {
            if (e.target.value.trim().length > 0) {
                e.target.parentElement.nextElementSibling.style.display = "block";
            }
        }
    });

    document.getElementById("category").addEventListener("change", function() {
        var searchInput = document.getElementById("keyword");
        if (searchInput.value.trim().length > 0) {
            searchFetch(searchInput);
        }
    });
    </script>';
        
    $css = '<style>
form.asearch {
        display: flex;
        max-width: 600px;
        margin: 0 auto;
}

form.asearch input#keyword {
        border: none;
        width: 100%;
        padding-left: 28px;
        border-radius: 30px;
        background-color: white;
        margin-right: -39px;
}
form.asearch button#mybtn {
        padding: 5px;
        cursor: pointer;
        background: white;
        border: none;
        margin-right: 20px;
        margin-top: 3px;
        box-shadow: 0px 0px 0px 0px rgba(0, 0, 0, 0);
}

form.asearch select#category {
        border: none;
        padding: 6px;
        width: 150px;
        min-width: 126px;
        cursor: pointer;
        background-color: white;
        margin-left: 10px;
        margin-top: 2px;
}

div#datafetch {
        background: white;
        z-index: 10;
        position: absolute;
        max-height: 425px;
        overflow: auto;
        box-shadow: 0px 15px 15px #00000036;
        width: 100%;
        max-width: 431px;
        top: 50px;
        padding: 5px 0;
}

div.search_result ul {
    padding: 0; 
    list-style: none;
    margin: 0;
}

div.search_result ul a {
    display: flex;
    justify-content: space-between; 
    align-items: center; 
    padding: 10px;
}

div.search_result ul a li {
    font-weight: bold; 
    color: #3f3f3f; 
    margin: 0; 
}

div.search_result ul a img {   
    height: 60px;
    width: auto;
    }
div.search_result ul a:hover { 
    background-color: #f3f3f3;

    } 

div#datafetch {
    background: white;
    z-index: 10;
    position: absolute;
    max-height: 425px;
    overflow: auto;
    box-shadow: 0px 15px 15px #00000036;
    width: 100%; /* Matches the width of the search bar */
    max-width: 431px; /* Ensure the results match the width of the search bar */
    top: 50px;
    padding: 5px 0; /* Reduced padding for a more compact result area */
    left: 559px;
    }
    
div.search_result ul a {
    display: -webkit-inline-box;
    justify-content: center;
    align-items: center;
    margin-bottom: 10px;
    width: 100%;
    padding: 10px 0; /* Adds some padding between result items */
    }
    
div.search_result ul a li {

    margin: 0px;
    padding: 0px;
    padding-inline-start: 18px;
    color: #3f3f3f;
    font-weight: bold;
    width: 75%; /*Adjust the title section width */
}
    
    div.search_result ul a img {
        height: 60px;
        padding: 0px 5px;
        width: auto;
        margin-left: 10px;
        float: right; /* Ensures the image is aligned to the right */
    }

    div.search_result ul a:hover {
        background-color: #f3f3f3;
    }
    div.search_result ul {
        padding: 0; /* Reduced padding for a more compact list */
        list-style: none; 
        margin: 0;
    }
    div.search_result .result-content {
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    div.search_result .result-content .title {
        font-weight: bold; 
        }
    div.search_result .result-content .description { 
        font-size: 12px;
        color: #777;
        margin-top: 5px;
        }

    div.search_bar {
        position: relative;
        }

    .description {
        font-size: small; 
        color: var(--ast-form-input-text,#475569); 
    }  
</style>';

    if ($asearch_first_call == 1) {
        $asearch_first_call++;
        return "{$sForam}{$java}{$css}";
    } elseif ($asearch_first_call > 1) {
        $asearch_first_call++;
        return "{$sForam}";
    }
}

add_action('wp_ajax_asearch', 'asearch');
add_action('wp_ajax_nopriv_asearch', 'asearch');
    function asearch() {
    $category = isset($_POST['category']) ? esc_attr($_POST['category']): '';
    $search_term = isset($_POST['s']) ? esc_attr($_POST['s']): '';
    
    $args = array(
        'posts_per_page' => 10,
        's' => $search_term,
        'post_type' => explode(",", esc_attr($_POST['source'])), 
    );

    if ($category) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'product_cat',
                'field' => 'slug',
                'terms' => $category
            )
        );
    }

    $the_query= new WP_Query($args);

    if ($the_query->have_posts()) {
        echo '<ul>';
        while ($the_query->have_posts()) {
            $the_query->the_post(); ?>
            <a href="<?php echo esc_url(post_permalink()); ?>">
                <?php $image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'single-post-thumbnail'); ?>
                <?php if ($image && !empty($image[0]) && trim(esc_attr($_POST['image '])) == "true") { ?> 
                    <img src="<?php the_post_thumbnail_url('thumbnail'); ?>" alt="<?php the_title(); ?>">
                <?php } ?>
                <li>
                    <?php the_title(); ?>
                    <div class="description"><?php echo wp_trim_words(get_the_excerpt(), 15, '...'); ?></div>
                </li>
            </a>
        <?php }
        echo '</ul>';
        wp_reset_postdata();
    } else {
        echo '<ul><a href="#"><li>Sorry, nothing found</li></a></ul>';
    }
    die();
}
?>