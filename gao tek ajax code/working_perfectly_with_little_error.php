<?php
// Enqueue parent theme styles
add_action('wp_enqueue_scripts', 'enqueue_parent_styles');
function enqueue_parent_styles() {
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
}

// Register the shortcode
add_shortcode('asearch', 'asearch_func');
function asearch_func($atts) {
    $atts = shortcode_atts(array(
        'source' => 'product', // Default post type to search
        'image' => 'true'      // Whether to display images
    ), $atts, 'asearch');

    static $asearch_first_call = 1;
    $source = $atts["source"];
    $image = $atts["image"];

    // Fetch product categories for the dropdown
    $categories = get_terms(array(
        'taxonomy' => 'product_cat',
        'hide_empty' => true,
    ));

    // Search form HTML
    $sForam = '<div class="search_bar">
        <form class="asearch" id="asearch' . $asearch_first_call . '" action="/" method="get" autocomplete="off">
            <input type="text" name="s" placeholder="Search products..." id="keyword" class="input_search">
            <button type="button" id="mybtn">🔍</button>
            <select name="category" id="category">
                <option value="">All Categories</option>';
    foreach ($categories as $category) {
        $sForam .= '<option value="' . $category->slug . '">' . $category->name . '</option>';
    }
    $sForam .= '</select>
        </form>
        <div class="search_result" id="datafetch">
            <ul><li>Please wait..</li></ul>
        </div>
    </div>';

    // JavaScript for AJAX functionality
    $java = '<script>
        function searchFetch() {
            var searchInput = document.getElementById("keyword");
            var categoryInput = document.getElementById("category");
            var datafetch = document.getElementById("datafetch");

            if (searchInput.value.trim().length > 0) {
                datafetch.style.display = "block";
            } else {
                datafetch.style.display = "none";
            }

            var formdata = new FormData();
            formdata.append("s", searchInput.value);
            formdata.append("source", "' . $source . '");
            formdata.append("image", "' . $image . '");
            formdata.append("category", categoryInput.value);
            formdata.append("action", "asearch");

            AjaxAsearch(formdata, searchInput);
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

        // Add event listener to the search button
        document.getElementById("mybtn").addEventListener("click", function() {
            searchFetch();
        });

        // Add event listener to the search input for real-time search
        document.getElementById("keyword").addEventListener("keyup", function() {
            searchFetch();
        });

        // Hide search results when clicking outside
        document.addEventListener("click", function(e) {
            if (!e.target.closest(".search_bar")) {
                document.getElementById("datafetch").style.display = "none";
            }
        });

        // Trigger search when category is changed
        document.getElementById("category").addEventListener("change", function() {
            searchFetch();
        });
    </script>';

    // CSS for styling the search results
    $css = '<style>
        .search_bar {
            position: relative;
            max-width: 600px;
            margin: 0 auto;
        }

        form.asearch {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        form.asearch input#keyword {
            border: 1px solid #ccc;
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            font-size: 16px;
        }

        form.asearch button#mybtn {
            padding: 8px 16px;
            cursor: pointer;
            background: #ebf3f7;
            border: 2px solid black;
            color: black;
            border-radius: 5px;
            font-size: 30px;
        }

        form.asearch select#category {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        .search_result {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            width: 100%;
            background: white;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            max-height: 300px;
            overflow-y: auto;
        }

        .search_result ul {
            padding: 0;
            margin: 0;
            list-style: none;
        }

        .search_result ul a {
            display: flex;
            align-items: center;
            padding: 10px;
            text-decoration: none;
            color: #333;
        }

        .search_result ul a:hover {
            background-color: #f9f9f9;
        }

        .search_result ul a img {
            width: 50px;
            height: 50px;
            margin-right: 10px;
            border-radius: 5px;
        }

        .search_result ul a .product-info {
            flex: 1;
        }

        .search_result ul a .product-info .title {
            font-weight: bold;
            font-size: 14px;
        }

        .search_result ul a .product-info .description {
            font-size: 12px;
            color: #777;
            margin-top: 5px;
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

// AJAX handler for search results
add_action('wp_ajax_asearch', 'asearch');
add_action('wp_ajax_nopriv_asearch', 'asearch');
function asearch() {
    $category = isset($_POST['category']) ? esc_attr($_POST['category']) : '';
    $search_term = isset($_POST['s']) ? esc_attr($_POST['s']) : '';

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

    $the_query = new WP_Query($args);

    if ($the_query->have_posts()) {
        echo '<ul>';
        while ($the_query->have_posts()) {
            $the_query->the_post();
            $product_image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'thumbnail');
            ?>
            <a href="<?php echo esc_url(get_permalink()); ?>">
                <?php if ($product_image && !empty($product_image[0])) : ?>
                    <img src="<?php echo esc_url($product_image[0]); ?>" alt="<?php the_title(); ?>">
                <?php endif; ?>
                <div class="product-info">
                    <div class="title"><?php the_title(); ?></div>
                    <div class="description"><?php echo wp_trim_words(get_the_excerpt(), 15, '...'); ?></div>
                </div>
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