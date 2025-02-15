jQuery(function ($) {
  "use strict";

  // email resend
  $(".resend").on("click", function (e) {
    // Button which will activate our modal
    var the_id = $(this).attr("id"); //get the id
    // show the spinner
    $(this).html("<i class='fa fa-spinner fa-pulse'></i>");
    $.ajax({
      //the main ajax request
      type: "POST",
      data: "action=email_verify&id=" + $(this).attr("id"),
      url: ajaxurl,
      success: function (data) {
        $("span#resend_count" + the_id).html(data);
        //fadein the vote count
        $("span#resend_count" + the_id).fadeIn();
        //remove the spinner
        $("a.resend_buttons" + the_id).remove();
      },
    });
    return false;
  });

  // user login
  $("#login-form").on("submit", function (e) {
    e.preventDefault();
    $("#login-status").slideUp();
    $("#login-button").addClass("button-progress").prop("disabled", true);
    var form_data = {
      action: "ajaxlogin",
      username: $("#username").val(),
      password: $("#password").val(),
      is_ajax: 1,
    };
    $.ajax({
      type: "POST",
      url: ajaxurl,
      data: form_data,
      dataType: "json",
      success: function (response) {
        $("#login-button")
          .removeClass("button-progress")
          .prop("disabled", false);
        if (response.success) {
          $("#login-status")
            .addClass("success")
            .removeClass("error")
            .html("<p>" + LANG_LOGGED_IN_SUCCESS + "</p>")
            .slideDown();
          window.location.href = response.message;
        } else {
          $("#login-status")
            .removeClass("success")
            .addClass("error")
            .html("<p>" + response.message + "</p>")
            .slideDown();
        }
      },
    });
    return false;
  });

  // blog comment with ajax
  $(".blog-comment-form").on("submit", function (e) {
    e.preventDefault();

    var action = "submitBlogComment";
    var data = $(this).serialize();
    var $parent_cmnt = $(this).find("#comment_parent").val();
    var $cmnt_field = $(this).find("#comment-field");
    var $btn = $(this).find(".button");
    $btn.addClass("button-progress").prop("disabled", true);

    $.ajax({
      type: "POST",
      url: ajaxurl + "?action=" + action,
      data: data,
      dataType: "json",
      success: function (response) {
        $btn.removeClass("button-progress").prop("disabled", false);
        if (response.success) {
          if ($parent_cmnt == 0) {
            $(".latest-comments > ul").prepend(response.html);
          } else {
            $("#li-comment-" + $parent_cmnt).after(response.html);
          }
          $("html, body").animate(
            {
              scrollTop: $("#li-comment-" + response.id).offset().top,
            },
            2000
          );
          $cmnt_field.val("");
        } else {
          $("#respond > .widget-content").prepend(
            '<div class="notification error"><p>' +
              response.error +
              "</p></div>"
          );
        }
      },
    });
  });

  /* generate content */
  $("#ai_form").on("submit", function (e) {
    e.preventDefault();
    e.stopPropagation();
    var action = "generate_content";
    var data = new FormData(this),
      $form = $(this);

    var $btn = $(this).find(".button"),
      $error = $(this).find(".form-error");
    $btn.addClass("button-progress").prop("disabled", true);

    $error.slideUp();
    $.ajax({
      type: "POST",
      url: ajaxurl + "?action=" + action,
      data: data,
      dataType: "json",
      cache: false,
      contentType: false,
      processData: false,
      success: function (response) {
        $btn.removeClass("button-progress").prop("disabled", false);
        if (response.success) {
          let old_content = tinymce.activeEditor.getContent();
          if (old_content) {
            old_content += "<br><br>";
          }
          tinymce.activeEditor.setContent(old_content + response.text);
          tinymce.activeEditor.focus();

          tinyMCE.activeEditor.selection.select(
            tinyMCE.activeEditor.getBody(),
            true
          );
          tinyMCE.activeEditor.selection.collapse(false);

          $(".simplebar-scroll-content").animate(
            {
              scrollTop: $("#content-focus").offset().top,
            },
            500
          );

          animate_value(
            "quick-words-left",
            response.old_used_words,
            response.current_used_words,
            4000
          );
        } else {
          $error.html(response.error).slideDown().focus();
        }
      },
    });
  });

  /* generate speech to text */
  $("#speech_to_text").on("submit", function (e) {
    e.preventDefault();
    e.stopPropagation();

    var action = "speech_to_text";
    var data = new FormData(this),
      $form = $(this);

    var $btn = $(this).find(".button"),
      $error = $(this).find(".form-error");
    $btn.addClass("button-progress").prop("disabled", true);

    $error.slideUp();
    $.ajax({
      type: "POST",
      url: ajaxurl + "?action=" + action,
      data: data,
      dataType: "json",
      cache: false,
      contentType: false,
      processData: false,
      success: function (response) {
        $btn.removeClass("button-progress").prop("disabled", false);
        if (response.success) {
          tinymce.activeEditor.setContent(response.text);
          tinymce.activeEditor.focus();

          tinyMCE.activeEditor.selection.select(
            tinyMCE.activeEditor.getBody(),
            true
          );
          tinyMCE.activeEditor.selection.collapse(false);

          $(".simplebar-scroll-content").animate(
            {
              scrollTop: $("#content-focus").offset().top,
            },
            500
          );

          animate_value(
            "quick-speech-left",
            response.old_used_speech,
            response.current_used_speech,
            1000
          );
        } else {
          $error.html(response.error).slideDown().focus();
        }
      },
    });
  });

  /* generate code */
  $("#ai_code").on("submit", function (e) {
    e.preventDefault();
    e.stopPropagation();

    var action = "ai_code";
    var data = new FormData(this),
      $form = $(this);

    var $btn = $(this).find(".button"),
      $error = $(this).find(".form-error");
    $btn.addClass("button-progress").prop("disabled", true);

    $error.slideUp();
    $.ajax({
      type: "POST",
      url: ajaxurl + "?action=" + action,
      data: data,
      dataType: "json",
      cache: false,
      contentType: false,
      processData: false,
      success: function (response) {
        $btn.removeClass("button-progress").prop("disabled", false);
        if (response.success) {
          $("#content-focus").html(response.text);

          $(".simplebar-scroll-content").animate(
            {
              scrollTop: $("#content-focus").offset().top,
            },
            500
          );
          hljs.highlightAll();

          animate_value(
            "quick-words-left",
            response.old_used_words,
            response.current_used_words,
            4000
          );
        } else {
          $error.html(response.error).slideDown().focus();
        }
      },
    });
  });

  /* save ai document */
  $("#ai_document_form").on("submit", function (e) {
    e.preventDefault();
    e.stopPropagation();
    var action = "save_document";
    var data = new FormData(this),
      $form = $(this);

    var $btn = $(this).find(".button"),
      $error = $(this).find(".form-error");
    $btn.addClass("button-progress").prop("disabled", true);

    $error.slideUp();
    $.ajax({
      type: "POST",
      url: ajaxurl + "?action=" + action,
      data: data,
      dataType: "json",
      cache: false,
      contentType: false,
      processData: false,
      success: function (response) {
        $btn.removeClass("button-progress").prop("disabled", false);
        if (response.success) {
          $form.find("#post_id").val(response.id);
          Snackbar.show({
            text: response.message,
            pos: "bottom-center",
            showAction: false,
            actionText: "Dismiss",
            duration: 3000,
            textColor: "#fff",
            backgroundColor: "#383838",
          });
        } else {
          $error.html(response.error).slideDown().focus();
        }
      },
    });
  });

  /* ai images */
  $("#ai_images").on("submit", function (e) {
    var tagId = $(".ai-tag-a.active").attr("data-id");

    e.preventDefault();
    e.stopPropagation();
    var action = "generate_image";
    var data = new FormData(this),
      $form = $(this);
    data.append("tagId", tagId);

    let imageNumber = data.get("no_of_images");

    var $btn = $(this).find(".button"),
      $error = $(this).find(".form-error");
    $btn.addClass("button-progress").prop("disabled", true);

    $error.slideUp();

    for (let j = 0; j < imageNumber; j++) {
      $.ajax({
        type: "POST",
        url: ajaxurl + "?action=" + action,
        data: data,
        dataType: "json",
        cache: false,
        contentType: false,
        processData: false,
        success: function (response) {
          $btn.removeClass("button-progress").prop("disabled", false);
          if (response.success) {
            $("#generated_images_notice").hide();
            $("#ai_images").trigger("reset");
            $("#generated_images_wrapper").html("");

            $.each(response.data, function (index, value) {
              $("#generated_images_wrapper").prepend(
                '<div class="col-sm-4 col-md-2 col-6 margin-bottom-30 icon_arrow" id="img-div-' +
                  value.id +
                  '">' +
                  '<a href="' +
                  value.image +
                  '" target="_blank" class="ai-lightbox-image" data-sub-html="<p>' +
                  value.description +
                  '</p>">' +
                  '<img width="100%" height="100%" src="' +
                  value.image +
                  '" alt="' +
                  value.description +
                  '" data-tippy-placement="top" title="' +
                  value.description +
                  '">' +
                  "</a>" +
                  '<div class="overlay delete-block-img" data-id="' +
                  value.id +
                  '" >' +
                  '<span class="fa fa-times-circle-o"></span>' +
                  "</div>" +
                  "</div>"
              );
            });

            /* refresh lightbox */
            window.lgData[$(".image-lightbox").attr("lg-uid")].destroy(true);
            lightGallery($(".image-lightbox").get(0), {
              selector: ".ai-lightbox-image",
              download: true,
            });
            animate_value(
              "quick-images-left",
              response.old_used_images,
              response.current_used_images,
              1000
            );

            $(".simplebar-scroll-content").animate(
              {
                scrollTop: $("#generated_images_wrapper").offset().top,
              },
              500
            );
            lightGallery($(".image-lightbox").get(0), {
              selector: ".ai-lightbox-image",
              download: true,
            });

            animate_value(
              "quick-images-left",
              response.old_used_images,
              response.current_used_images,
              1000
            );

            $(".simplebar-scroll-content").animate(
              {
                scrollTop: $("#generated_images_wrapper").offset().top,
              },
              500
            );
          } else {
            $error.html(response.error).slideDown().focus();
          }
        },
      });
    }
  });

  /* ai text to speeches */
  $(".speech-text").on("click", function (e) {
    e.preventDefault();
    e.stopPropagation();

    var action = "text_to_speech";
    var form = $("#ai_text_speech")[0];
    var data = new FormData(form);

    var $btn = $(this),
      $error = $(form).find(".form-error");

    var $audioContainer = $(form).find(".audio-container");
    var $audio = $audioContainer.find(".preview_audio");

    if ($btn.attr("id") === "generateButton") {
      action = "text_to_speech";
    } else if ($btn.attr("id") === "previewButton") {
      action = "preview_text_to_speech";
    }

    $btn.addClass("button-progress").prop("disabled", true);

    $error.slideUp();
    $.ajax({
      type: "POST",
      url: ajaxurl + "?action=" + action,
      data: data,
      dataType: "json",
      cache: false,
      contentType: false,
      processData: false,
      success: function (response) {
        $btn.removeClass("button-progress").prop("disabled", false);
        if (response.success) {
          if ($btn.attr("id") === "generateButton") {
            location.reload();
          } else {
            // Handle the "Preview" action here
            $audio.attr(
              "src",
              "data:audio/mpeg;base64," + response.audio_stream
            );
            $audioContainer.show();

            // Add the "autoplay" attribute to the audio element
            $audio.attr("autoplay", "autoplay");

            // Add a callback when the audio has started playing
            $audio[0].addEventListener("play", function () {
              // Listen for the "ended" event to hide the audio container when playback ends
              $audio[0].addEventListener("ended", function () {
                $audioContainer.hide();
              });
            });
          }
        } else {
          $error.html(response.error).slideDown().focus();
        }
      },
    });
  });
  // preview text to speech
  $("#previewForm").on("submit", function (e) {
    e.preventDefault();
    e.stopPropagation();

    alert("---");
    var action = "preview_text_to_speech";
    var data = new FormData(this),
      $form = $(this);

    var $btn = $(this).find(".button"),
      $error = $(this).find(".form-error");
    $btn.addClass("button-progress").prop("disabled", true);

    $error.slideUp();
    $.ajax({
      type: "POST",
      url: ajaxurl + "?action=" + action,
      data: data,
      dataType: "json",
      cache: false,
      contentType: false,
      processData: false,
      success: function (response) {
        $btn.removeClass("button-progress").prop("disabled", false);
        if (response.success) {
          // location.reload();
        } else {
          $error.html(response.error).slideDown().focus();
        }
      },
    });
  });

  /* delete ajax */
  $(".quick-delete").on("click", function (e) {
    e.preventDefault();
    e.stopPropagation();

    var $btn = $(this);
    var action = $btn.data("action");

    if (confirm(LANG_ARE_YOU_SURE)) {
      $btn.addClass("button-progress").prop("disabled", true);
      $.ajax({
        type: "POST",
        url: ajaxurl + "?action=" + action,
        data: {
          id: $btn.data("id"),
        },
        dataType: "json",
        success: function (response) {
          $btn.removeClass("button-progress").prop("disabled", false);
          if (response.success) {
            $btn.closest("tr").fadeOut("slow", function () {
              $(this).remove();
            });

            Snackbar.show({
              text: response.message,
              pos: "bottom-center",
              showAction: false,
              actionText: "Dismiss",
              duration: 3000,
              textColor: "#fff",
              backgroundColor: "#383838",
            });
          }
        },
      });
    }
  });

  function animate_value(id, start, end, duration) {
    start = parseInt(start);
    end = parseInt(end);
    if (start === end) return;
    var range = end - start;
    var current = parseInt(start);
    var increment = end > start ? 1 : -1;
    var stepTime = Math.abs(Math.floor(duration / range));
    var obj = document.getElementById(id);
    var timer = setInterval(function () {
      current += increment;
      obj.innerHTML = current;
      if (current == end) {
        clearInterval(timer);
      }
    }, stepTime);
  }
});
