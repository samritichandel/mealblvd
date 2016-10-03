<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Listable
 */

get_header(); ?>

<div id="primary" class="error_main">
	     <section class="error-404 not-found">
	     <div class="container">
           <div class="row">
            <div class="col-sm-12">
             <div class="error_section"><h1>Page Not Found</h1>   
              <p>The page you requested could not be found.</p>
             </div> 
             </div>   
            </div>
           </div>	
         </section>
        <div class="error_main_section">
         <div class="container">
             <div class="row">
              <div class="col-sm-12">
               <div class="error_btm_cntnt">      
               <h1>404</h1>  
                <p>We are sorry, but the page you were looking for doesn't exist.</p>
              </div>   
             </div>
            </div> 
         </div>
	   </div>
</div>   

<?php get_footer(); ?>
