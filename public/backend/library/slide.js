(function ($) {
    "use strict";
    var HT = {};
    var counter = 1;


    HT.addSlide = (type) => {
        $(document).on('click', '.addSlide', function (e) {
            e.preventDefault();
            if(typeof (type) == 'undefined') {
                type = 'Images';
            }
            var finder = new CKFinder();
            finder.resourceType = type;
            finder.selectActionFunction = function (fileUrl, data, allFiles) {
                let html = ''
                for (var i = 0; i < allFiles.length; i++) {
                    let image = allFiles[i].url
                    html += HT.renderSlideItem(image)
                }
                $('.slide-list').append(html)
                HT.checkSlideNotification()


            }
            finder.popup();
        })
    }
    HT.checkSlideNotification = () => {
        let slideItem = $('.slide-item')
        if (slideItem.length) {
            $('.slide-notification').hide()
        } else {
            $('.slide-notification').show()
        }
    }
    HT.renderSlideItem = (image) => {
        let tab_1 = "tab_" + counter
        let tab_2 = "tab_" + (counter + 1)
        let html = `<div class="col-lg-12 ui-state-default">
                        <div class="slide-item mb20">
                            <div class="row custom-row">
                                <div class="col-lg-3 mb-10">
                                    <span class="slide-image img-cover">
                                        <img src="${image}" alt="">
                                            <input type="hidden" name="slide[image][]" value="${image}">
                                            <span class="deleteSlite btn btn-danger"><i class="fa fa-trash"></i></span>
                                            </span>
                                        </div>
                                        <div class="col-lg-9">
                                            <div class="tabs-container">
                                                <ul class="nav nav-tabs">
                                                    <li class="active"><a data-toggle="tab" href="#${tab_1}" aria-expanded="true"> Thông tin chung</a></li>
                                                    <li class=""><a data-toggle="tab" href="#${tab_2}" aria-expanded="false">SEO</a></li>
                                                </ul>
                                                <div class="tab-content">
                                                    <div id="${tab_1}" class="tab-pane active">
                                                        <div class="panel-body">
                                                            <div class="label-text mb5">
                                                                Mô tả
                                                            </div>
                                                            <div class="form-row mb10">
                                                                <textarea name="slide[description][]" class="form-control"></textarea>
                                                            </div>
                                                            <div class="form-row form-row-url">
                                                                <input
                                                                    type="text"
                                                                    name="slide[canonical][]"
                                                                    class="form-control"
                                                                    placeholder="URL"
                                                                >
                                                                    <div class="overlay">
                                                                        <div class="uk-flex uk-flex-middle">
                                                                            <label for="input_${tab_1}">Mở trong tab mới</label>
                                                                            <input
                                                                                type="checkbox"
                                                                                name="slide[window][]"
                                                                                value="_blank"
                                                                                id="input_${tab_1}"
                                                                            >
                                                                        </div>
                                                                    </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div id="${tab_2}" class="tab-pane">
                                                        <div class="panel-body">
                                                            <div class="label-text mb5">
                                                                Tiêu đề ảnh
                                                            </div>
                                                            <div class="form-row form-row-url slide-seo-tab">
                                                                <input
                                                                    type="text"
                                                                    name="slide[name][]"
                                                                    class="form-control"
                                                                    placeholder="Tiêu đề ảnh"
                                                                >
                                                            </div>
                                                            <div class="label-text mt12 mb5">
                                                                Mô tả ảnh
                                                            </div>
                                                            <div class="form-row form-row-url slide-seo-tab">
                                                                <input
                                                                    type="text"
                                                                    name="slide[alt][]"
                                                                    class="form-control"
                                                                    placeholder="Mô tả ảnh"
                                                                >
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                </div>
                            </div>
                            <hr>
                        </div>`
        counter += 2;
        return html
    }
    HT.deleteSlite = () => {
        $(document).on('click', '.deleteSlite', function () {
            let _this = $(this)
            _this.parents('.ui-state-default').remove()
            HT.checkSlideNotification()
        })
    }

    $(document).ready(function () {
        HT.addSlide()
        HT.deleteSlite()
    })
})(jQuery);





