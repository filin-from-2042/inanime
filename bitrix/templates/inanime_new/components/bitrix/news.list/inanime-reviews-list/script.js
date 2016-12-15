
window.InanimeReviewList = function(params)
{
    this.ajaxURL = params.ajaxURL;
    this.timer = null;
    this.elementsPerPage = params.elementsPerPage;
};

window.InanimeReviewList.prototype.ddSelectFilter = function(element)
{
    inanime_new.ddSetSelectedText(element);

    var pageNumber = $('.ia-reviews-list .pagination-container .pagination .active a').first().text();
    this.filterList(pageNumber);
};

window.InanimeReviewList.prototype.pagSelectFilter = function(event)
{
    event.preventDefault();
    var elementVal = $('.ia-reviews-list .pagination-container .pagination .active a').first().text();
    var elementType = $(event.currentTarget).attr('aria-label');
    if(elementType=="Previous")
    {
        elementVal = parseInt(elementVal)-1
    }
    else if(elementType=="Next")
    {
        elementVal = parseInt(elementVal)+1;
    }
    else
    {
        elementVal = $(event.currentTarget).text();
    }
    this.filterList(elementVal);
};

window.InanimeReviewList.prototype.filterList = function(pageNumber)
{
    if(!!this.timer)
    {
        clearTimeout(this.timer);
    }
    var that = this;
    this.timer = setTimeout(function(){
        var filterData = {};
        filterData['tag'] = $('.ia-reviews-list .select-container.tag .btn .sort-value.hidden').text();

        var sortVal =  $('.ia-reviews-list .select-container.order .btn .sort-value.hidden').text();
        var splittedVal = sortVal.split(';');
        var sortData = {sortField:splittedVal[0], sortType:splittedVal[1]};
        that.getReviewFilterPage(filterData, sortData, pageNumber);
    },  500);

};

window.InanimeReviewList.prototype.getReviewFilterPage = function(filterData, sortData, pageNumber)
{
    var that = this;
    var postData = {
        "PAGEN_1" : pageNumber,
        "sort_data": JSON.stringify(sortData),
        "filter_data": JSON.stringify(filterData),
        "page_element_count": String(this.elementsPerPage),
        sessid:BX.bitrix_sessid()
    };
    $.ajax({
        url: this.ajaxURL,
        method: 'POST',
        data: postData,
        beforeSend: function() {
            window.inanime_new.inProgress = true;
            $(".ia-reviews-list").append('<div id="overlay-load"></div>');
        }
    }).done(function(data){
        $('.ia-reviews-list #overlay-load').remove();
        $('.reviews-list.ia-reviews-list').replaceWith($(data).find('.reviews-list.ia-reviews-list'));
        $('.ia-reviews-list .pagination-container .pagination a').click(function(event){
            that.pagSelectFilter(event);
        });
    });
};