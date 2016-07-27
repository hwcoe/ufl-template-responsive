<script type='text/javascript'>
var _gaq = _gaq || [];
_gaq.push(['_setAccount', '<?php esc_attr_e(of_get_option('opt_analytics_acct')); ?>']);
_gaq.push(['_addOrganic','search.ufl.edu','query']);
_gaq.push(['_trackPageview']);
_gaq.push(['_trackPageLoadTime']);

(function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
})();
            
jQuery(document).ready(function($) {
    $('a').click(function() {
        var href = $(this).attr('href');
        if (href.match(/^http/) && !href.match(document.domain)) {
            _gaq.push( [ '_trackEvent', 'Outbound Links', 'Click', href ] );
        }

        if (href.match(/\.(doc|docx|pdf|ppt|pptx|xls|xlsx|zip)$/)) {
            _gaq.push( [ '_trackEvent', 'Downloads', 'Download', href ] );
        }
    });

    $('#institutional-nav a').click(function() {
        _gaq.push( [ '_trackEvent', 'Global Header', 'Click', $(this).attr('href') ] );
    });

    $('#institutional-footer a').click(function() {
        _gaq.push( [ '_trackEvent', 'Global Footer', 'Click', $(this).attr('href') ] );
    });
});
</script>
