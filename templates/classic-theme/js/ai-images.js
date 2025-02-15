(function ($) {
    "use strict";

    var liLenght = $('.ai-img-tag li').length;
    if (liLenght <= 0) { createDefaultTag(); }

    // Function to create default tag
    function createDefaultTag () {
        $(".ai-tag-a").closest("li").removeClass("active-message");
        $(".ai-tag-a").removeClass("active");

        $(".ai-img-tag").prepend(
            `<li class="active-message ai-tag-li">
                <a href="javascript:void(0)" class="conversation ai-tag-a active">
                    <div class="message-by margin-left-0">
                        <div class="message-by-headline">
                            <h5>New Tag</h5>
                            <input class="conversation-title ai-tag-title with-border small-input" type="text" value="New Tag">
                            <span class="ai-tag-edit conversation-edit"><i class="icon-feather-edit"></i> Edit </span>
                        </div>
                    </div>
                </a>
            </li>`
        );

        $.ajax({
            type: "POST",
            url: ajaxurl + "?action=create_new_tag",
            data: { tag: 'New Tag'},
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    $(".ai-tag-a.active").attr("data-id", response.conv_id);
                    $(".ai-tag-a.active .message-by-headline").html(`<h5 style="">New Tag</h5>
                    <input class="conversation-title ai-tag-title with-border small-input" type="text" value="New Tag" style="display: none;" data-listener-added_562ecb70="true">
                    <span class="ai-tag-edit conversation-edit"><i class="icon-feather-edit"></i> Edit</span>`);

                    activeTagImage(response.conv_id);
                } else {
                    $error.html(response.error).slideDown().focus();
                }
            },
        });
    }

    // create new tags
    $('body').off("click", "#new-tag-create").on("click", "#new-tag-create", function (e) {
        e.preventDefault();
        e.stopPropagation();

        createDefaultTag()
    });


    $('body').off("click", ".ai-tag-edit").on("click", ".ai-tag-edit", function (e) {
        e.preventDefault();
        e.stopPropagation();
        let tagEdit = $(this).closest("li");

        tagEdit.find(".ai-tag-title").show().focus();
        tagEdit.find("h5").hide();
    })
    // On blur
    .on("blur", ".ai-tag-title", update_tag_name)
    // On enter
    .on("keypress", ".ai-tag-title", function (e) {
        var code = e.keyCode || e.which;
        if (code == 13) {
            update_tag_name.apply(this);
        }
    });

    function update_tag_name() {
        var $this = $(this),
            $item = $this.closest("li"),
            $edit_icon = $item.find(".ai-tag-edit"),
            $name = $item.find("h5"),
            value = $this.val(),
            id = $item.find("a").data("id"),
            data = { id: id, tag: value, tag_old: $('#tag-h5-'+id).text() };

        if ($name.text() != value) {
            $edit_icon.addClass("button-loader button-loader-dark").prop("disabled", true);
            $.post(ajaxurl + "?action=edit_tag_name", data, function () {
                $edit_icon.removeClass("button-loader button-loader-dark").prop("disabled", false);
            });
            // $('#tag-section-name').text('AI Image - '+$(".ai-tag-a").find(closest("li.active-message")).text());
        }
        // Show modified tag name.
        $name.text(value);
        // Hide input field.
        $this.hide();
        $name.show();
    }

    // search tag from the tag list
    $(".ai-tag-search").on("keyup", function (e) {
        e.preventDefault(); 
        e.stopPropagation();
        var searchTag = $(this).val().toLowerCase();
        $(".ai-img-tag").find("li").each(function () {
            if (
                $(this).filter(function () {
                    return ($(this).find("h5").text().toLowerCase().indexOf(searchTag) > -1);
                }).length > 0 || searchTag.length < 1
            ) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    // delete tag
    $('body').off("click", "#delete-tag").on("click", '#delete-tag', function (e) {
        e.preventDefault();
        e.stopPropagation();

        var btn = $(this);
        var tag_id = $(".ai-tag-a.active").attr("data-id");

        if (confirm(LANG_ARE_YOU_SURE)) {
            btn.addClass("button-progress").prop("disabled", true);
            $.ajax({
                type: "POST",
                url: ajaxurl +"?action=delete_ai_tags&tagId=" +tag_id,
                dataType: "json",
                success: function (response) {
                    btn.removeClass("button-progress").prop("disabled", false);
                    if (response.success) {
                        $(".ai-tag-a.active").closest("li").remove();
                        $(".ai-tag-a:eq(0)").closest("li").addClass("active-message");
                        $(".ai-tag-a:eq(0)").addClass("active");
                        
                        var tagLiLen = $('.ai-img-tag li').length;
                        if (tagLiLen <= 0) { createDefaultTag(); }
                        setTimeout(function () {
                            activeTagImage(tag_id, 'delete');
                        },900);
                    }
                },
            });
        }
    });

    // get active tag images
    $('body').off("click", ".ai-tag-li").on('click', '.ai-tag-li', function (e) {
        e.preventDefault(); 
        e.stopPropagation();

        $(".ai-tag-a").closest("li").removeClass("active-message");
        $(".ai-tag-a").removeClass("active");

        $(this).addClass("active-message");
        $(this).find('.ai-tag-a').addClass("active");
        var tagId = $(this).find('.ai-tag-a').attr("data-id");
        var tagText = $(this).find('#tag-h5-'+tagId).text();
        
        if(tagId.length > 0) {
            activeTagImage(tagId);
        }
    });
    function activeTagImage (tagId, actionFrom = "") {
        var url = ajaxurl +"?action=get_images_by_tag&tagId="+tagId;
        if(actionFrom === 'delete') { url = url+ "&actionFrom="+actionFrom; }

        $.ajax({
            type: "POST",
            url: url,
            dataType: "json",
            success: function (result) {
                if(result.success) {
                    var htmlData = "";
                    $.each(result.data, function (index, value) {
                        htmlData += '<div class="col-sm-4 col-md-2 col-6 margin-bottom-30 icon_arrow" id="img-div-'+value.id+'">'+
                            '<a href="'+value.image+'" target="_blank" class="ai-lightbox-image" data-sub-html="<p>'+value.description +'</p>">'+
                                '<img width="100%" height="100%" src="'+value.image +'" alt="'+value.description+'" data-tippy-placement="top" title="'+value.description +'">' +
                            "</a>"+
                            '<div class="overlay delete-block-img" data-id="'+value.id+'" >'+
                                '<span class="fa fa-times-circle-o"></span>'+
                            '</div>'+
                        '</div>';
                    });

                    setTimeout(function () {
                        /* refresh lightbox */
                        window.lgData[$(".image-lightbox").attr("lg-uid")].destroy(true);
                        lightGallery($(".image-lightbox").get(0), {
                            selector: ".ai-lightbox-image",
                            download: true,
                        });
                        // animate_value(
                        //     "quick-images-left",
                        //     result.old_used_images,
                        //     result.current_used_images,
                        //     1000
                        // );    
                        $(".simplebar-scroll-content").animate({
                            scrollTop: $("#generated_images_wrapper").offset().top,
                        },500);
                    },300);
                    // $('#tag-section-name').text('AI Images - '+response.activeTag);
                    $("#generated_images_wrapper").html("").html(htmlData)
                }
            },
        });
    }

    // delete specific images
    $('body').off("click", ".delete-block-img").on("click", ".delete-block-img", function (e) {
        e.preventDefault(); 
        e.stopPropagation();

        var btn = $(this);
        var imgId = $(this).attr("data-id");

        if (confirm(LANG_ARE_YOU_SURE)) {
            btn.addClass("button-progress").prop("disabled", true);
            $.ajax({
                type: "POST",
                url: ajaxurl +"?action=delete_image",
                data: {id: imgId},
                dataType: "json",
                success: function (response) {
                    btn.removeClass("button-progress").prop("disabled", false);
                    if (response.success) {
                        $('#img-div-'+imgId).remove();
                    }
                },
            });
        }
    });

})(jQuery);