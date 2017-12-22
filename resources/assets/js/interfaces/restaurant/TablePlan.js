export default function (options) {

    let sectionTabs = $(".section-tab");
    let tabContent = $('.tab-content');
    let sectionContents = $('.section-content');
    let $showMoreSections = $('.js-restaurant-plan__header-sections-show');
    let contentWidth = $('section.content').width();
    $(".owl-carousel").owlCarousel({
        items:3,
        autoWidth: true,
    });
    $(".owl-carousel").find('.owl-stage-outer').css('max-width', Math.max( contentWidth - 200 , 500 )  );

    // Make first tab active
    sectionTabs.first().addClass("active");
    sectionContents.first().addClass("in active");

    tabContent.fadeIn('slow');

    $('#table_plan_select').change(function () {
        window.location = '/restaurant/table-plans/' + $(this).val();
    });

    $showMoreSections.on('click', function(){
       $('.owl-carousel').trigger('next.owl.carousel');
    });
    $('.owl-stage-outer').wrap('<div class="owl-overflow"></div>');

    tabContent.tablesM({
        'tablePlanObjects': options.tablePlanObjects,
        'tablePlanId': options.tablePlanId,
        'deleteObjectMsg': options.deleteObjectMsg
    }).init();

}