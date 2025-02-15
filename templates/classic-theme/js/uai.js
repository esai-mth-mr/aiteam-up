(function ($) {
  "use strict";

  // manage create-uai-agent-form hide show content
  $("#general-form").removeClass("d-none");
  $(".uai-general-form").addClass("gradient-btn-1");
  $("#knowledge-form").addClass("d-none");
  $(".uai-knowledge-form").removeClass("gradient-btn-1");
  $("#create_uai_agent_modal .uai-form")
    .css("overflow-x", "hidden")
    .css("overflow-y", "auto");

  $("body").on("click", ".uai-consent-btn", function () {
    $(".uai-consent-tab").addClass("d-none");

    $("#general-form").removeClass("d-none");
    $(".uai-general-form").addClass("gradient-btn-1");
    $("#knowledge-form").addClass("d-none");
    $(".uai-knowledge-form").removeClass("gradient-btn-1");

    if ($(this).data("consent") === "general") {
      $("#knowledge-form").addClass("d-none");
      $("#general-form").removeClass("d-none");
      $(".uai-general-form").addClass("gradient-btn-1");
      $(".uai-knowledge-form").removeClass("gradient-btn-1");
      $("#create_uai_agent_modal .uai-form")
        .css("overflow-x", "hidden")
        .css("overflow-y", "auto");
    }
    if ($(this).data("consent") === "knowledge") {
      $("#create_uai_agent_modal .uai-form")
        .css("overflow-x", "hidden")
        .css("overflow-y", "hidden")
        .animate({ scrollTop: 0 }, "fast");
      $("#general-form").addClass("d-none");
      $("#knowledge-form").removeClass("d-none");
      $(".uai-general-form").removeClass("gradient-btn-1");
      $(".uai-knowledge-form").addClass("gradient-btn-1");
    }
  });
  // manage create-uai-agent-form hide show content end

  $("body").on("click", ".create_uai_folder_btn", function () {
    // $('.uai_section_content').addClass('d-none');
    // $('.create_uai_folder_div').removeClass('d-none');
    // $('#directory_name').focus();
    $("#create_uai_folder_modal").modal("show");
  });

  $("body").on("click", ".create_uai_agent_btn", function () {
    // $('.uai_section_content').addClass('d-none');
    // $('.create_uai_agent_div').removeClass('d-none');
    $("#create_uai_agent_modal").modal("show");
  });

  // Create Training Directory and Store in Database
  $("body").on("click", ".proceed_create_directory", function () {
    var directoryName = $("#directory_name").val();

    if (directoryName.length <= 0) {
      alert("Directory name should not be blank.");
      return false;
    }
    $.ajax({
      url: ajaxurl + "?action=create_uai_directory",
      type: "POST",
      data: { directoryName: directoryName },
      beforeSend: function () {
        $(this).addClass("button-progress").prop("disabled", true);
      },
      success: function (res) {
        var result = JSON.parse(res);
        if (result.success) {
          var htmlData = "";
          if (result.dir_list.length > 0) {
            $.each(result.dir_list, function (key, value) {
              htmlData +=
                '<a href="javascript:void(0)" class="show_training_document" id="directory-id-' +
                value.id +
                '" data-id="' +
                value.id +
                '" data-text="' +
                value.folder_name +
                '">' +
                '<div class="icon-strip-box">' +
                '<div class="isb-left-wrap">' +
                '<i class="fa-regular fa-folder mr-5"></i> ' +
                value.folder_name +
                ' <span class="ml-5" id="charsCount_' +
                value.id +
                '"></span>' +
                "</div>" +
                '<div class="isb-right-wrap"><span id="listCount_' +
                value.id +
                '"></span>&nbsp;<i class="fa-solid fa-trash-can ml-5 delete_uai_agent"></i></div>' +
                "</div>" +
                "</a>";
            });
          } else {
            htmlData =
              '<a href="javascript:void(0)">' +
              '<div class="d-block icon-strip-box text-center">' +
              '<div class="isb-left-wrap"> No Folder Created Yet </div>' +
              "</div>" +
              "</a>";
          }
          setTimeout(function () {
            $("body").find("#directories-list").html("").html(htmlData);
          }, 700);
          // $('.uai_section_content').addClass('d-none');
          $("#create_uai_folder_modal").modal("hide");
          $("#directory_name").val("");
        }
      },
      error: function () {},
      complete: function () {
        $(this).removeClass("button-progress").prop("disabled", false);
      },
    });
  });

  // Search training directory
  $("#search_training_directory").on("keyup", function () {
    var searchTerm = $(this).val().toLowerCase();
    $("#directories-list")
      .find("a")
      .each(function () {
        if (
          $(this).filter(function () {
            return (
              $(this)
                .find("#folder-text-name")
                .text()
                .toLowerCase()
                .indexOf(searchTerm) > -1
            );
          }).length > 0 ||
          searchTerm.length < 1
        ) {
          $(this).show();
        } else {
          $(this).hide();
        }
      });
  });

  // Delete UAi Folder
  $("body").on("click", ".delete_uai_folder", function () {
    var directoryId = $(this).closest("a").data("id");
    if (confirm(LANG_ARE_YOU_SURE)) {
      $.ajax({
        url: ajaxurl + "?action=delete_uai_directory",
        type: "GET",
        data: { directoryId: directoryId },
        beforeSend: function () {
          $(this).addClass("button-progress").prop("disabled", true);
        },
        success: function (res) {
          var result = JSON.parse(res);
          if (result.success) {
            if (result.dirCount <= 0) {
              var htmlData =
                '<a href="javascript:void(0)">' +
                '<div class="d-block icon-strip-box text-center">' +
                '<div class="isb-left-wrap"> No Folder Created Yet </div>' +
                "</div>" +
                "</a>";
              $("body").find("#directories-list").html("").html(htmlData);
            }
            setTimeout(function () {
              $("#directory-id-" + directoryId).remove();
              $(".uai_section_content").addClass("d-none");
            }, 500);
          }
        },
        error: function () {},
        complete: function () {
          $(this).removeClass("button-progress").prop("disabled", false);
        },
      });
    }
  });

  // Delete UAi Agent
  $("body").on("click", ".delete_uai_agent", function () {
    var agentId = $(this).closest("a").data("id");
    if (confirm(LANG_ARE_YOU_SURE)) {
      $.ajax({
        url: ajaxurl + "?action=delete_uai_agent",
        type: "GET",
        data: { agentId: agentId },
        beforeSend: function () {
          $(this).addClass("button-progress").prop("disabled", true);
        },
        success: function (res) {
          var result = JSON.parse(res);
          if (result.success) {
            if (result.dirCount <= 0) {
              var htmlData =
                '<a href="javascript:void(0)">' +
                '<div class="d-block icon-strip-box text-center">' +
                '<div class="isb-left-wrap"> No Folder Created Yet </div>' +
                "</div>" +
                "</a>";
              $("body").find("#directories-list").html("").html(htmlData);
            }
            setTimeout(function () {
              $("#uai-agent-" + agentId).remove();
              $(".uai_section_content").addClass("d-none");
            }, 500);
          }
        },
        error: function () {},
        complete: function () {
          $(this).removeClass("button-progress").prop("disabled", false);
        },
      });
    }
  });

  // Function to get training document list
  function getTrainingDocumentList(uaiAgentId) {
    $("#store-document-list").html("");
    $.ajax({
      url: ajaxurl + "?action=show_training_document_list",
      type: "GET",
      data: { uaiAgentId: uaiAgentId },
      success: function (res) {
        var result = JSON.parse(res);
        var tBodyTr = "";
        if (result.success) {
          $.each(result.documents, function (key, value) {
            tBodyTr +=
              '<tr id="document-tr-' +
              value.id +
              '">' +
              "<td>" +
              value.title +
              "</td>" +
              "<td>Created</td>" +
              "<td>" +
              value.created_at +
              "</td>" +
              "<td>";
            if (value.action !== "write") {
              if (value.action === "upload") {
                tBodyTr +=
                  '<a href="javascript:void(0)" class="button ripple-effect btn-sm view_uai_document" data-tippy-placement="top" data-tippy="" data-original-title="View UAi Training Document" data-id="' +
                  value.id +
                  '" data-action="' +
                  value.action +
                  '" data-value="' +
                  value.title +
                  '"><i class="fa fa-eye"></i></a>&nbsp;';
              } else {
                tBodyTr +=
                  '<a href="javascript:void(0)" class="button ripple-effect btn-sm view_uai_document" data-tippy-placement="top" data-tippy="" data-original-title="View UAi Training Document" data-id="' +
                  value.id +
                  '" data-action="' +
                  value.action +
                  '" data-value="' +
                  value.title +
                  '"><i class="fa fa-eye"></i></a>&nbsp;';
              }
            } else {
              tBodyTr +=
                '<a href="javascript:void(0)" class="button ripple-effect btn-sm edit_uai_document" data-tippy-placement="top" data-tippy="" data-original-title="Edit UAi Training Document" data-id="' +
                value.id +
                '"><i class="fa fa-edit"></i></a>&nbsp;';
            }
            tBodyTr +=
              '<a href="javascript:void(0)" class="button ripple-effect btn-sm red delete_uai_document" data-tippy-placement="top" data-tippy="" data-original-title="Delete UAi Training Document" data-id="' +
              value.id +
              '"><i class="fa fa-trash"></i></a></td>';
            ("</tr>");
          });
        } else {
          tBodyTr =
            "<tr>" +
            '<td colspan="4" class="text-center"> No document created yet </td>' +
            "</tr>";
        }
        // $('#charsCount_'+uaiAgentId+'').text('('+result.word_count+')');
        // $('#listCount_'+uaiAgentId+'').text(result.doc_count);
        $("#store-document-list").html("").html(tBodyTr);
      },
      error: function () {},
    });
  }

  // Get Training document by directory name
  $("body").on("click", ".show_training_document", function () {
    var uaiAgentId = $(this).data("id");
    var uaiAgentName = $(this).data("text");
    var agentChatBotId = $(this).data("aichatbot");

    $(".uai_section_content").addClass("d-none");
    $(".folder_training_document").removeClass("d-none");
    $("#uai_agent_name").text(uaiAgentName + " :");
    $("#agent_id").val(uaiAgentId);
    $("#agent_chatbot_id").val(agentChatBotId);

    $("body").find(".show_training_document").removeClass("link-active");
    $("body")
      .find("#uai-agent-" + uaiAgentId + "")
      .addClass("link-active");
    $("#training-document-form").trigger("reset");

    getTrainingDocumentList(uaiAgentId);
  });

  // Hide show form Fields By Action
  $("body").on("click", ".training-doc-action", function () {
    $(".training-doc-action").removeClass("active");
    $(this).addClass("active");
    var sectionDiv = $(this).data("section");
    if (sectionDiv === "write") {
      $("body").find(".training-document-section").addClass("d-none");
      $("body").find("#write_document_section").removeClass("d-none");
    }
    if (sectionDiv === "upload") {
      $("body").find(".training-document-section").addClass("d-none");
      $("body").find("#upload_document_section").removeClass("d-none");
    }
    if (sectionDiv === "import") {
      $("body").find(".training-document-section").addClass("d-none");
      $("body").find("#import_document_section").removeClass("d-none");
    }
  });

  // Get selected file name and show in the specific place
  $("body").on("change", "#upload_file", function () {
    $("body").find("#choosen_file").text("");
    $("body")
      .find("#upload_document_section .upload-box")
      .css("padding", "20px 0 0 0", "important");

    if ($("#upload_file")[0].files[0].name.length > 0) {
      var fileName = $("#upload_file")[0].files[0].name;
      $("#choosen_file").text(fileName).css("color", "red", "important");
      $("body")
        .find("#upload_document_section .upload-box")
        .css("padding", "20px 0 20px 0", "important");
    }
  });

  // Create Training document
  $("body").on("click", ".create-document-btn", function () {
    var btn = $(this);
    var form = new FormData();
    var sectionType = $("body").find(".action-card.active").data("section");

    form.append("section", sectionType);
    form.append("agentId", $("#agent_id").val());
    form.append("agentChatbotId", $("#agent_chatbot_id").val());

    if (sectionType === "write") {
      if ($("#doc_title").val().length <= 0) {
        alert("Document Title should not be blank.");
        return false;
      }

      form.append("title", $("#doc_title").val());
      form.append("content", tinymce.get("document_content").getContent());
    }

    if (sectionType === "upload") {
      if ($("#upload_file")[0].files.length <= 0) {
        alert("Please select the file to upload.");
        return false;
      }

      form.append("file_name", $("#upload_file")[0].files[0].name);
      form.append("file_content", $("#upload_file")[0].files[0]);
    }

    if (sectionType === "import") {
      if ($("#website_url").val().length <= 0) {
        alert("Website url should not be blank.");
        return false;
      }

      form.append("web_url", $("#website_url").val());
    }

    $.ajax({
      url: ajaxurl + "?action=create_training_document",
      type: "POST",
      data: form,
      dataType: "json",
      cache: false,
      contentType: false,
      processData: false,
      beforeSend: function () {
        btn.addClass("button-progress").prop("disabled", true);
      },
      success: function (res) {
        getTrainingDocumentList($("#agent_id").val());
        $("#training-document-form").trigger("reset");
        alert(res.message);

        if (sectionType === "upload") {
          $("body").find("#choosen_file").text("");
          $("body")
            .find("#upload_document_section .upload-box")
            .css("padding", "20px 0 0 0", "important");
        }
      },
      error: function (err) {},
      complete: function () {
        btn.removeClass("button-progress").prop("disabled", false);
      },
    });
  });

  // Create UAi Agent
  $("body").on("click", ".proceed_create_agent", function () {
    var btn = $(this);
    var form = new FormData();

    var agentName = $("#agent_name").val();
    var agentGender = $("#agent_gender").val();
    var agentRole = $("#agent_role").val();
    var agentRoleDesc = $("#agent_role_desc").val();
    var agentTone = $("#agent_tone").val();
    var agentResStyle = $("#agent_response_style").val();

    if (
      agentName.length <= 0 ||
      agentGender.length <= 0 ||
      agentRole.length <= 0 ||
      agentRoleDesc.length <= 0 ||
      agentTone.length <= 0 ||
      agentResStyle.length <= 0 /* || 
            (agentFolder.length <= 0)*/
    ) {
      alert(
        "Please provide complete details of Agent Such as Name, Role, Description & Knowledge to create UAi Agent!"
      );
      return false;
    }
    if ($("#agent_image")[0].files.length <= 0) {
      alert("Please choose the Agent Image to upload.");
      return false;
    }
    form.append("agentName", agentName);
    form.append("agentGender", agentGender);
    form.append("agentImage", $("#agent_image")[0].files[0]);
    form.append("agentRole", agentRole);
    form.append("agentRoleDesc", agentRoleDesc);
    form.append("agentTone", agentTone);
    form.append("agentResStyle", agentResStyle);
    // form.append('agentFolder', agentFolder);

    $.ajax({
      url: ajaxurl + "?action=create_uai_agent",
      type: "POST",
      data: form,
      cache: false,
      contentType: false,
      processData: false,
      beforeSend: function () {
        btn.addClass("button-progress").prop("disabled", true);
      },
      success: function (ress) {
        var ress = JSON.parse(ress);
        if (ress.success) {
          $("body").find("#uai-agent-create-form").trigger("reset");
          alert(ress.message);
          getUaiAgentList();

          $("#create_uai_agent_modal").modal("hide");
          $("body")
            .find(".uai-folder-list span")
            .removeClass("active-selected");
          $(".check-all-folder").prop("checked", false);

          $("#general-form").removeClass("d-none");
          $(".uai-general-form").addClass("gradient-btn-1");
          $("#knowledge-form").addClass("d-none");

          $(".uai-knowledge-form").removeClass("gradient-btn-1");
          $("#create_uai_agent_modal .uai-form")
            .css("overflow-x", "hidden")
            .css("overflow-y", "auto");
          $("body")
            .find(".show-select-uai-image")
            .html("")
            .html(
              '<img src="https://reeelapps-app.s3.us-west-2.amazonaws.com/aistaff/embed_logo/default_staff.png" style="width: auto; height: 100%; padding: 10px; border-radius: 28px; background: rgb(199, 199, 199);">'
            );
        } else {
          var error = $("body").find("#form-error");
          error.html(ress.message).slideDown().focus();
          return false;
        }
      },
      error: function (errr) {},
      complete: function () {
        btn.removeClass("button-progress").prop("disabled", false);
      },
    });
  });

  // get UAi Agent list
  function getUaiAgentList() {
    $.ajax({
      url: ajaxurl + "?action=get_uai_agent_list",
      type: "GET",
      success: function (res) {
        var result = JSON.parse(res);
        console.log("check-result:: ", result);
        if (result.success) {
          var htmlData = "";
          if (result.data.length > 0) {
            $.each(result.data, function (key, value) {
              htmlData +=
                '<a href="javascript:void(0)" class="show_training_document" id="uai-agent-' +
                value.id +
                '" data-id="' +
                value.id +
                '" data-text="' +
                value.agent_name +
                '" data-aichatbot="' +
                value.ai_chat_bot_id +
                '">' +
                '<div class="icon-strip-box">' +
                '<div class="isb-left-wrap">' +
                '<i class="fa-regular fa-folder mr-5"></i> ' +
                value.agent_name +
                ' <span class="ml-5" id="charsCount_' +
                value.id +
                '"></span>' +
                "</div>" +
                '<div class="isb-right-wrap">' +
                '<i class="fa-solid fa-pencil edit_uai_agent_btn" data-tippy-placement="top" data-tippy data-original-title="Edit" title="Edit"></i>&nbsp;' +
                '<i class="fa-solid fa-trash-can ml-5 delete_uai_agent" data-tippy-placement="top" data-tippy data-original-title="Delete" title="Delete"></i>' +
                "</div>" +
                "</div>" +
                "</a>";
            });
          } else {
            htmlData =
              '<a href="javascript:void(0)">' +
              '<div class="d-block icon-strip-box text-center">' +
              '<div class="isb-left-wrap"> No UAi Agent Created Yet </div>' +
              "</div>" +
              "</a>";
          }
          setTimeout(function () {
            $("body").find("#directories-list").html("").html(htmlData);
          }, 700);
          // $('.uai_section_content').addClass('d-none');
          $("#create_uai_folder_modal").modal("hide");
          $("#directory_name").val("");
        }
      },
      error: function () {},
      complete: function () {
        $(this).removeClass("button-progress").prop("disabled", false);
      },
    });
  }

  // Delete UAi Document
  $("body").on("click", ".delete_uai_document", function () {
    var documentId = $(this).data("id");
    if (confirm(LANG_ARE_YOU_SURE)) {
      var btn = $(this);
      $.ajax({
        url: ajaxurl + "?action=delete_uai_document",
        type: "GET",
        data: { documentId: documentId },
        beforeSend: function () {
          btn.addClass("button-progress").prop("disabled", true);
        },
        success: function (res) {
          var result = JSON.parse(res);
          if (result.success) {
            setTimeout(function () {
              $("#document-tr-" + documentId).remove();
              alert(result.message);
              getTrainingDocumentList(result.agentId);
            }, 500);
          }
        },
        error: function () {},
        complete: function () {
          btn.removeClass("button-progress").prop("disabled", false);
        },
      });
    }
  });
  
  $("body").on("click", ".embed_uai_bot_btn", function () {


    const botId = $(this).data("bot_id");
    
    $("#embed-modal").data("bot_id", botId);

    $("#embed-modal").modal("show");
  })

  // View UAi Document
  $("body").on("click", ".view_uai_document", function () {
    var action = $(this).data("action");
    var value = $(this).data("value");
    var rootPath = $("#root_path").val();

    if (action === "upload") {
      window.open("/aiteamup/storage/uai/" + value, "_blank");
    }
    if (action === "import") {
      console.log(value)
      window.open(value, "_blank");
    }
  });

  // allow all folder to checked
  $("body").on("click", ".check-all-folder", function () {
    if ($(this).is(":checked") == true) {
      $("body").find(".uai-folder-list span").addClass("active-selected");
    } else {
      $("body").find(".uai-folder-list span").removeClass("active-selected");
    }
  });

  // manage folder selection
  $("body").on("click", ".uai-folder-span", function () {
    if ($(this).hasClass("active-selected") == true) {
      $(this).removeClass("active-selected");
    } else {
      $(this).addClass("active-selected");
    }
    setTimeout(function () {
      var folderCount = $(".check-all-folder").data("folder-count");
      var selectedFolder = $(".active-selected").length;
      if (selectedFolder < folderCount) {
        $(".check-all-folder").prop("checked", false);
      } else {
        $(".check-all-folder").prop("checked", true);
      }
    }, 300);
  });

  // show selected image
  $("body").on("change", "#agent_image", function (e) {
    var selectedFile = e.target.files[0];
    if (selectedFile) {
      var reader = new FileReader();

      reader.onload = function (e) {
        $("body")
          .find(".show-select-uai-image")
          .html("")
          .html(
            '<img src="' +
              e.target.result +
              '" style="width: auto; height: 100%; padding: 10px; border-radius: 28px; background: rgb(199, 199, 199);">'
          );
      };
      reader.readAsDataURL(selectedFile);
    }
  });

  // edit uploaded documents
  $("body").on("click", ".edit_uai_document", function () {
    var documentId = $(this).data("id");
    $.ajax({
      url: ajaxurl + "?action=edit_uai_document",
      type: "GET",
      data: { documentId: documentId },
      success: function (res) {
        var result = JSON.parse(res);
        if (result.success) {
          var document = result.document;

          $("#edit_document_type").val(document.action);
          $("#edit_document_id").val(document.id);
          $("#edit_ai_chat_bot").val(document.ai_chat_bot_id);
          if (document.content != null) {
            console.log("check-result-if:: ", result);
            tinymce.get("edit_document_content").setContent(document.content);
          }
          setTimeout(function () {
            $("#edit_document_textarea_modal").modal("show");
          }, 200);
        }
      },
      error: function () {},
    });
  });

  // update uploaded documents
  $("body").on("click", ".uai-document-update", function () {
    var btn = $(this);
    var documentId = $("body").find("#edit_document_id").val();
    var documentType = $("body").find("#edit_document_type").val();
    var docuemtnContent = tinymce.get("edit_document_content").getContent();

    if (docuemtnContent.length <= 0) {
      alert("Document content should not be blank.");
      return false;
    } else {
      $.ajax({
        url: ajaxurl + "?action=update_uai_document",
        type: "POST",
        data: {
          documentId: documentId,
          documentType: documentType,
          docuemtnContent: docuemtnContent,
        },
        beforeSend: function () {
          btn.addClass("button-progress").prop("disabled", true);
        },
        success: function (res) {
          var result = JSON.parse(res);
          console.log("check-result:: ", result);
          if (result.success) {
            setTimeout(function () {
              tinymce.get("edit_document_content").setContent("");
              $("#edit_document_textarea_modal").modal("hide");
            }, 200);
            alert(result.message);
          }
        },
        error: function () {},
        complete: function () {
          btn.removeClass("button-progress").prop("disabled", false);
        },
      });
    }
  });

  // show selected image
  $("body").on("change", "#edit_agent_image", function (e) {
    var selectedFile = e.target.files[0];
    if (selectedFile) {
      var reader = new FileReader();

      reader.onload = function (e) {
        $("body")
          .find("#show-select-uai-image")
          .html("")
          .html(
            '<img src="' +
              e.target.result +
              '" style="width: auto; height: 100%; padding: 10px; border-radius: 28px; background: rgb(199, 199, 199);">'
          );
      };
      reader.readAsDataURL(selectedFile);
    }
  });

  $("body").on("change", ".edit_agent_gender", function (e) {
    var agentGender = $(this).val();
    localStorage.setItem("agentGender", agentGender);
  });

  // edit modal form for uai agent information
  $("body").on("click", ".edit-uai-consent-btn", function () {
    $(".edit-uai-consent-tab").addClass("d-none");

    if ($(this).attr("data-consent") === "general") {
      $("#edit_uai_agent_modal .uai-form")
        .css("overflow-x", "hidden")
        .css("overflow-y", "auto");
      $("#edit-general-form").removeClass("d-none");
      $("#edit-knowledge-form").addClass("d-none");
      $(".edit_uai_general_form").addClass("gradient-btn-1");
      $(".edit_uai_knowledge_form").removeClass("gradient-btn-1");
    }
    if ($(this).attr("data-consent") === "knowledge") {
      $("body").find(".edit-uai-consent-btn");
      $("#edit_uai_agent_modal .uai-form")
        .css("overflow-x", "hidden")
        .css("overflow-y", "hidden")
        .animate({ scrollTop: 0 }, "fast");
      $("#edit-general-form").addClass("d-none");
      $("#edit-knowledge-form").removeClass("d-none");
      $(".edit_uai_general_form").removeClass("gradient-btn-1");
      $(".edit_uai_knowledge_form").addClass("gradient-btn-1");
    }
  });

  // fetch uai agent information behalf of UAI AGENT ID
  $("body").on("click", ".edit_uai_agent_btn", function () {
    $("#edit_uai_agent_modal .uai-form")
      .css("overflow-x", "hidden")
      .css("overflow-y", "auto");
    $("#edit-general-form").removeClass("d-none");
    $("#edit-knowledge-form").addClass("d-none");
    $(".edit_uai_general_form").addClass("gradient-btn-1");
    $(".edit_uai_knowledge_form").removeClass("gradient-btn-1");

    var agentId = $(this).closest("a").data("id");
    $.ajax({
      url: ajaxurl + "?action=edit_uai_agent",
      type: "GET",
      data: { agentId: agentId },
      success: function (res) {
        var result = JSON.parse(res);
        if (result.success) {
          $("body").find("#edit_agent_name").val(result.uaiAgent.agent_name);
          $("body")
            .find(
              '.edit_agent_gender[value="' + result.uaiAgent.agent_gender + '"]'
            )
            .prop("checked", true);
          localStorage.setItem("agentGender", result.uaiAgent.agent_gender);
          $("body").find("#edit_agent_role").val(result.uaiAgent.agent_role);
          $("body")
            .find("#edit_agent_role_desc")
            .val(result.uaiAgent.role_description);
          $("body")
            .find("#show-select-uai-image")
            .html("")
            .html(
              '<img src="' +
                result.siteUrl +
                "storage/bot_images/" +
                result.uaiAgent.agent_image +
                '" style="width: auto; height: 100%; padding: 10px; border-radius: 28px; background: rgb(199, 199, 199);" />'
            );

          $("body").find("#edit_uai_agent_id").val(result.uaiAgent.id);
          $("body")
            .find("#edit_uai_agent_img")
            .val(result.uaiAgent.agent_image);
          $("body").find("#edit_agent_tone").val(result.uaiAgent.tone).change();
          $("body")
            .find("#edit_agent_response_style")
            .val(result.uaiAgent.response_style)
            .change();

          $("#edit_uai_agent_modal").modal("show");
        }
      },
      error: function () {},
      complete: function () {
        $(this).removeClass("button-progress").prop("disabled", false);
      },
    });
  });

  $("body").on("click", ".proceed_update_agent", function () {
    var btn = $(this);
    var form = new FormData();

    var agentName = $("#edit_agent_name").val();
    var agentGender = localStorage.getItem("agentGender");
    var agentRole = $("#edit_agent_role").val();
    var agentRoleDesc = $("#edit_agent_role_desc").val();
    var agentTone = $("#edit_agent_tone").val();
    var agentResStyle = $("#edit_agent_response_style").val();
    var agentId = $("#edit_uai_agent_id").val();
    var agentImg = $("#edit_uai_agent_img").val();

    console.log("check-agentGender:: ", agentGender);

    if (
      agentName.length <= 0 ||
      agentGender.length <= 0 ||
      agentRole.length <= 0 ||
      agentRoleDesc.length <= 0 ||
      agentTone.length <= 0 ||
      agentResStyle.length <= 0
    ) {
      alert(
        "Please provide complete details of Agent Such as Name, Role, Description & Knowledge to create UAi Agent!"
      );
      return false;
    }
    if ($("#edit_agent_image")[0].files.length <= 0 && agentImg.length <= 0) {
      alert("Please choose the Agent Image to upload.");
      return false;
    } else if ($("#edit_agent_image")[0].files.length > 0) {
      var agentImage = $("#edit_agent_image")[0].files[0];
      form.append("agentImage", agentImage);
    }
    form.append("agentName", agentName);
    form.append("agentGender", agentGender);
    form.append("agentRole", agentRole);
    form.append("agentRoleDesc", agentRoleDesc);
    form.append("agentTone", agentTone);
    form.append("agentResStyle", agentResStyle);
    form.append("agentId", agentId);
    form.append("agentImg", agentImg);

    $.ajax({
      url: ajaxurl + "?action=update_uai_agent",
      type: "POST",
      data: form,
      cache: false,
      contentType: false,
      processData: false,
      beforeSend: function () {
        btn.addClass("button-progress").prop("disabled", true);
      },
      success: function (ress) {
        var ress = JSON.parse(ress);
        if (ress.success) {
          localStorage.removeItem("agentGender");
          $("body").find("#uai-agent-update-form").trigger("reset");
          alert(ress.message);
          getUaiAgentList();
          $("#uai_agent_name").text(agentName + ":");
          $("#edit_uai_agent_modal").modal("hide");

          $("#edit-general-form").removeClass("d-none");
          $("#edit_uai_general_form").addClass("gradient-btn-1");
          $("#edit-knowledge-form").addClass("d-none");
          $("#edit_uai_knowledge_form").removeClass("gradient-btn-1");
          $("#edit_uai_agent_modal .uai-form")
            .css("overflow-x", "hidden")
            .css("overflow-y", "auto");

          $("body")
            .find("#show-select-uai-image")
            .html("")
            .html(
              '<img src="https://reeelapps-app.s3.us-west-2.amazonaws.com/aistaff/embed_logo/default_staff.png" style="width: auto; height: 100%; padding: 10px; border-radius: 28px; background: rgb(199, 199, 199);">'
            );
        }
      },
      error: function (errr) {},
      complete: function () {
        btn.removeClass("button-progress").prop("disabled", false);
      },
    });
  });

  /***********************Chat History Start */

  $(document).ready(function () {
    // Sample newest and latest dates

    const lastDate = new Date($(".sorting-tool-bar").data("last-time"));
    const lastDateTemp = new Date($(".sorting-tool-bar").data("last-time"));
    const firstDate = new Date($(".sorting-tool-bar").data("first-time"));
    const selectedFirstDate = new Date(
      $(".sorting-tool-bar").data("selected_start_date")
    );
    const selectedEndDate = new Date(
      $(".sorting-tool-bar").data("selected_end_date")
    );

    if (
      selectedFirstDate == "Invalid Date" ||
      selectedEndDate == "Invalid Date"
    ) {
      // Set default text of date-picker
      setDatePickerText(firstDate, lastDate);
    } else {
      setDatePickerText(selectedFirstDate, selectedEndDate);
    }

    // Generate date list items
    generateDateList(lastDate, firstDate);

    // Handle click event on the date picker
    $("#date-picker").on("click", function (e) {
      // Toggle visibility of the date list
      $("#date-list").toggle();
    });

    // Handle click event on date-list items
    $(document).on("click", ".date-list-item", function () {
      //   // Update default text of date-picker with selected date
      var selectedStartDate = new Date($(this).data("start-date"));
      var selectedEndDate = new Date($(this).data("end-date"));

      let siteUrl = $(".sorting-tool-bar").data("site-url");
      let agentId = $(".sorting-tool-bar").data("agent_id");

      window.location.href =
        siteUrl +
        "uai/chat-history/?id=" +
        agentId +
        `&start=${selectedStartDate}&end=${selectedEndDate}`;

      //   setDatePickerText(selectedStartDate, selectedEndDate);

      //   console.log(selectedStartDate);
      //   console.log(selectedEndDate);
      //   // Hide the date list
      //   $("#date-list").hide();
    });

    $("#all-chat-history").on("click", function () {
      setDatePickerText(firstDate, lastDateTemp);
    });

    // Function to set default text of date-picker
    function setDatePickerText(start, end) {
      var startDateText = formatDate(start);
      var endDateText = formatDate(end);

      $("#date-picker-text").text(startDateText + " - " + endDateText);
    }

    // Function to generate date list items
    function generateDateList(newest, latest) {
      var dateList = $("#date-list");
      dateList.empty(); // Clear existing items

      // Generate date list items per week (Monday to Sunday)
      for (var i = newest; i >= latest; i.setDate(i.getDate() - 7)) {
        var startDate = getMonday(new Date(i));
        var endDate = getSunday(new Date(i));

        var listItem = $("<div>")
          .addClass("date-list-item")
          .data("start-date", startDate)
          .data("end-date", endDate)
          .text(formatDate(startDate) + " - " + formatDate(endDate));

        dateList.append(listItem);
      }
    }

    // Function to get the Monday of the week
    function getMonday(date) {
      var day = date.getDay();
      var diff = date.getDate() - day + (day === 0 ? -6 : 1); // adjust when day is Sunday
      return new Date(date.setDate(diff));
    }

    // Function to get the Sunday of the week
    function getSunday(date) {
      var day = date.getDay();
      var diff = date.getDate() - day + 7; // adjust when day is Sunday
      return new Date(date.setDate(diff));
    }

    // Function to format date as "Mon DD, YYYY"
    function formatDate(date) {
      var options = { year: "numeric", month: "short", day: "2-digit" };
      return date.toLocaleDateString("en-US", options);
    }

    document.querySelectorAll(".embed_list_item").forEach(function (item) {
      item.addEventListener("click", function () {
        // Remove embed_selected class from all items
        document
          .querySelectorAll(".embed_list_item")
          .forEach(function (otherItem) {
            otherItem.classList.remove("embed_selected");
          });

        // Add embed_selected class to the clicked item
        this.classList.add("embed_selected");
      });
    });

    $(".embed_list_item").on("click", function (e) {
      let embedId = $(this).find(".quick-check").data("embed_id");
      let agentId = $(".sorting-tool-bar").data("agent_id");

      $.ajax({
        url: ajaxurl + "?action=get_chat_history",
        type: "GET",
        data: { embed_id: embedId, agent_id: agentId },
        beforeSend: function () {
          $(this).addClass("button-progress").prop("disabled", true);
        },
        success: function (res) {
          var result = JSON.parse(res);
          if (result.success) {
            $(".cis-right-wrap").empty();

            if (result.chat_history.length > 0) {
              let chatHistory = result.chat_history;

              chatHistory.forEach((chat) => {
                var htmlData =
                  '<div style="display: flex; flex-direction: row-reverse;">' +
                  '<div class="mr-15 mt-15" style="width: fit-content; max-width: 80%; background-color: #367df2; color: white; padding: 10px; border-top-left-radius: 10px; border-top-right-radius: 10px; border-bottom-left-radius: 10px;">' +
                  `<a>${chat.user_message}</a>` +
                  "</div>" +
                  "</div>" +
                  `<div class="message-container" id="chat-id-${chat.chat_id}" data-chat-id="${chat.chat_id}">` +
                  '<div class="ml-15 mt-15" style="width: fit-content; max-width: 80%; background-color: #e9e9e9; padding: 10px; border-top-left-radius: 15px; border-top-right-radius: 15px; border-bottom-right-radius: 15px;">' +
                  `<a class="ai-message">${chat.ai_message}</a>` +
                  '<textarea type="text" class="ai-message-input" style="display: none;"></textarea>' +
                  "</div>" +
                  '<a class="revise-answer-btn ml-15 mt-15 revise-answer" style="color: #367df2; margin-top: 5px; font-size: 12px; cursor: pointer;">Incorrect? Revise answer.</a>' +
                  "</div>";

                $(".cis-right-wrap").append(htmlData);
              });
            }
          }
        },
        error: function () {},
        complete: function () {
          $(this).removeClass("button-progress").prop("disabled", false);
        },
      });
    });

    $(document).ready(function () {
      $(document).on("click", ".revise-answer-btn", function () {
        // Hide the <a> element inside the same parent and show the <input> element
        var $parent = $(this).closest(".message-container");
        $parent.find(".ai-message").hide();
        $parent.find(".ai-message-input").show();

        // Set the value of the <input> to the current AI message
        var currentAiMessage = $parent.find(".ai-message").text();
        $parent.find(".ai-message-input").val(currentAiMessage);
      });

      // Handle saving the revised answer on input field blur
      $(document).on("blur", ".ai-message-input", function () {
        saveRevisedAnswer($(this));
      });

      // // Handle saving the revised answer on "Enter" key press
      // $(document).on("keydown", ".ai-message-input", function (e) {
      //   if (e.key === "Enter") {
      //     console.log("sss");
      //     saveRevisedAnswer($(this));
      //   }
      // });

      function saveRevisedAnswer($input) {
        // Get the revised answer from the input
        var revisedAnswer = $input.val();

        // Update the <a> with the revised answer
        var $parent = $input.closest(".message-container");
        var $aiMessage = $parent.find(".ai-message");
        var originalContent = $aiMessage.text(); // Save the original content for rollback on failure
        var agentId = $(".sorting-tool-bar").data("agent_id");

        $aiMessage.text(revisedAnswer);

        // Hide the <input> and show the <a> again
        $input.hide();
        $aiMessage.show();

        // Send the updated content to the server using Axios
        var chatId = $parent.data("chat-id");

        $.ajax({
          url: ajaxurl + "?action=edit_chat_history",
          type: "GET",
          data: { chat_id: chatId, message: revisedAnswer, agent_id: agentId },
          success: function (res) {
            var result = JSON.parse(res);
            if (result.success) {
            } else {
              $aiMessage.text(originalContent);
            }
          },
        });
      }
    });
  });

  let embedIds = [];

  $(".quick-check").on("click", function (e) {
    let embedId = $(this).data("embed_id");

    let embedIdIndex = embedIds.indexOf(embedId);
    if (embedIdIndex != -1) {
      embedIds.splice(embedIdIndex, 1);
    } else {
      embedIds.push(embedId);
    }

    if (embedIds.length > 0) {
      $("#chat_history_delete").show();
    } else {
      $("#chat_history_delete").hide();
    }
  });

  $("#chat_history_delete").on("click", function (e) {
    $(this).addClass("button-progress").prop("disabled", true);

    $.ajax({
      url: ajaxurl + "?action=delete_chat_history",
      type: "POST",
      data: { embed_ids: embedIds },
      success: (res) => {
        var result = JSON.parse(res);
        if (result.success) {
          window.location.reload();
        } else {
          Snackbar.show({
            text: result.error,
            pos: "bottom-center",
            showAction: false,
            actionText: "Dismiss",
            duration: 3000,
            textColor: "#fff",
            backgroundColor: "#383838",
          });
        }
      },
      error: function () {},
      complete: function () {
        $(this).removeClass("button-progress").prop("disabled", false);
      },
    });
  });

  $("#chat_history_export_to_word").on("click", function (e) {
    e.preventDefault();

    let button = $(this);

    button.addClass("button-progress").prop("disabled", true);

    let embedId = $(".embed_selected").find(".quick-check").data("embed_id");

    $.ajax({
      url: ajaxurl + "?action=word_export_chat_history",
      type: "POST",
      data: { embedId },
      success: (res) => {
        var result = JSON.parse(res);
        if (result.success) {
          let text = result.text;

          var preHtml =
            "<html xmlns:o='urn:schemas-microsoft-com:office:office' xmlns:w='urn:schemas-microsoft-com:office:word' xmlns='http://www.w3.org/TR/REC-html40'><head><meta charset='utf-8'><title>Export HTML To Doc</title></head><body>";
          var postHtml = "</body></html>";
          var html = preHtml + text + postHtml;

          var blob = new Blob(["\ufeff", html], {
            type: "application/msword",
          });

          // Specify link url
          var url =
            "data:application/vnd.ms-word;charset=utf-8," +
            encodeURIComponent(html);

          // Specify file name
          var filename = "document.doc";

          // Create download link element
          var downloadLink = document.createElement("a");

          document.body.appendChild(downloadLink);

          if (navigator.msSaveOrOpenBlob) {
            navigator.msSaveOrOpenBlob(blob, filename);
          } else {
            // Create a link to the file
            downloadLink.href = url;

            // Setting the file name
            downloadLink.download = filename;

            //triggering the function
            downloadLink.click();
          }

          document.body.removeChild(downloadLink);
        } else {
          Snackbar.show({
            text: result.error,
            pos: "bottom-center",
            showAction: false,
            actionText: "Dismiss",
            duration: 3000,
            textColor: "#fff",
            backgroundColor: "#383838",
          });
        }
      },
      error: function () {},
      complete: function () {
        button.removeClass("button-progress").prop("disabled", false);
      },
    });
  });

  $("#chat_history_export_to_txt").on("click", function (e) {
    e.preventDefault();

    let button = $(this);

    button.addClass("button-progress").prop("disabled", true);

    let embedId = $(".embed_selected").find(".quick-check").data("embed_id");

    $.ajax({
      url: ajaxurl + "?action=txt_export_chat_history",
      type: "POST",
      data: { embedId },
      success: (res) => {
        var result = JSON.parse(res);
        if (result.success) {
          let txt = result.text;

          // replace br with \n
          var regex = /<br\s*[\/]?>/gi;
          txt = txt.replace(regex, "\n");

          // remove html tags
          txt = $("<div>" + txt + "</div>").text();

          var downloadableLink = document.createElement("a");
          downloadableLink.setAttribute(
            "href",
            "data:text/plain;charset=utf-8," + encodeURIComponent(txt)
          );
          downloadableLink.download = "Text File" + ".txt";
          document.body.appendChild(downloadableLink);
          downloadableLink.click();
          document.body.removeChild(downloadableLink);
        } else {
          Snackbar.show({
            text: result.error,
            pos: "bottom-center",
            showAction: false,
            actionText: "Dismiss",
            duration: 3000,
            textColor: "#fff",
            backgroundColor: "#383838",
          });
        }
      },
      error: function () {},
      complete: function () {
        button.removeClass("button-progress").prop("disabled", false);
      },
    });
  });
})(jQuery);
