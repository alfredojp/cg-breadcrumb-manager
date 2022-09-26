
const { __, _x, _n, _nx } = wp.i18n;

function get_attached_taxonomy(p, callback) {
  var tax_arr = [];
  tax_arr['post'] = {'blog-topic': __( 'Blog Topic', 'cg-breadcrumb-manager-admin' ), 'brand': __( 'Brand', 'cg-breadcrumb-manager-admin' ), 'service': __( 'Service', 'cg-breadcrumb-manager-admin' ), 'industry': __( 'Industry', 'cg-breadcrumb-manager-admin' ), 'partners': __( 'Partner', 'cg-breadcrumb-manager-admin' )};
  tax_arr['event'] = {'brand': __( 'Brand', 'cg-breadcrumb-manager-admin' )};
  tax_arr['press-release'] = {'press-release-type': __( 'Press Release Type', 'cg-breadcrumb-manager-admin' ), 'brand': __( 'Brand', 'cg-breadcrumb-manager-admin' )};
  tax_arr['people'] = {'people-type': __( 'People Type', 'cg-breadcrumb-manager-admin' ), 'brand': __( 'Brand', 'cg-breadcrumb-manager-admin' ), 'service': __( 'Service', 'cg-breadcrumb-manager-admin' ), 'industry': __( 'Industry', 'cg-breadcrumb-manager-admin' ), 'partners': __( 'Partner', 'cg-breadcrumb-manager-admin' )};
  tax_arr['employee-testimonial'] = {'brand': __( 'Brand', 'cg-breadcrumb-manager-admin' ), 'country': __( 'Country', 'cg-breadcrumb-manager-admin' ), 'grade': __( 'Grade', 'cg-breadcrumb-manager-admin' ), 'job_family': __( 'Job Famliy', 'cg-breadcrumb-manager-admin' )};
  tax_arr['story'] = {'story-theme': __( 'Story Theme', 'cg-breadcrumb-manager-admin' )};
  tax_arr['location'] = {};
  tax_arr['client-story'] = {'brand': __( 'Brand', 'cg-breadcrumb-manager-admin' ), 'service': __( 'Service', 'cg-breadcrumb-manager-admin' ), 'industry': __( 'Industry', 'cg-breadcrumb-manager-admin' ), 'partners': __( 'Partner', 'cg-breadcrumb-manager-admin' ), 'country': __( 'Country', 'cg-breadcrumb-manager-admin' )};
  tax_arr['research-and-insight'] = {'research-and-insight-type': __( 'Research & Insight Type', 'cg-breadcrumb-manager-admin' ), 'theme': __( 'Theme', 'cg-breadcrumb-manager-admin' ), 'brand': __( 'Brand', 'cg-breadcrumb-manager-admin' ), 'service': __( 'Service', 'cg-breadcrumb-manager-admin' ), 'industry': __( 'Industry', 'cg-breadcrumb-manager-admin' ), 'partners': __( 'Partner', 'cg-breadcrumb-manager-admin' )};
  tax_arr['resource'] = {'resource-type': __( 'Resource Type', 'cg-breadcrumb-manager-admin' ), 'brand': __( 'Brand', 'cg-breadcrumb-manager-admin' ), 'service': __( 'Service', 'cg-breadcrumb-manager-admin' ), 'industry': __( 'Industry', 'cg-breadcrumb-manager-admin' ), 'partners': __( 'Partner', 'cg-breadcrumb-manager-admin' )};
  tax_arr['analyst-report'] = {'analyst': __( 'Analyst', 'cg-breadcrumb-manager-admin' ), 'service': __( 'Service', 'cg-breadcrumb-manager-admin' ), 'industry': __( 'Industry', 'cg-breadcrumb-manager-admin' ), 'partners': __( 'Partner', 'cg-breadcrumb-manager-admin' )};

  if ( tax_arr[p] !== undefined ) {
    var el = jQuery("#breadcrumb_manager_setting_field_taxonomy");
    jQuery("#breadcrumb_manager_setting_field_taxonomy > option").each(function(i) {
      if ( i > 0 ) {
        jQuery(this).remove();
      }
    });
    jQuery("#breadcrumb_manager_setting_field_taxonomy_term > option").each(function(i) {
      if ( i > 0 ) {
        jQuery(this).remove();
      }
    });
    jQuery.each( tax_arr[p], function( key, value ) {
      el.append(new Option(value, key));
    });
  } else {
    if ( p !== '' ) {
      alert(__( "Taxonomy not defined for this post type!", 'cg-breadcrumb-manager-admin' ));
    }

    jQuery("#breadcrumb_manager_setting_field_taxonomy").val('');
    jQuery("#breadcrumb_manager_setting_field_taxonomy_term").val('');
  }

  if ( callback !== undefined ) {
    callback();
  }
}

function get_taxonomy_terms(t, callback) {
  var blogPath = ( breadcrumb_manager_var.pathName !== undefined && breadcrumb_manager_var.pathName !== '' ) ? '/' + js_var.pathName : '';
  if ( t !== '' ) {
    var el = jQuery("#breadcrumb_manager_setting_field_taxonomy_term");
    jQuery("#breadcrumb_manager_setting_field_taxonomy_term > option").each(function(i) {
      if ( i > 0 ) {
        jQuery(this).remove();
      }
    });
    jQuery.ajax({
      url: blogPath + "/wp-json/wp/v2/" + t + "?_fields=id,name",
    })
    .done(function( data ) {
      data.map(service => {
        el.append(new Option(unescape(service.name), service.id));
      });
      if ( callback !== undefined ) {
        callback();
      }
    });
  }
}

jQuery(document).ready(function() {
  jQuery("#breadcrumb_manager_setting_field_post_type").on("change", function() {
    get_attached_taxonomy(this.value);
  });

  jQuery("#breadcrumb_manager_setting_field_taxonomy").on("change", function() {
    get_taxonomy_terms(this.value);
  });

  jQuery("#breadcrumb_manager_form").submit(function(e) {

    var result = [];
    var edit_id = jQuery("#breadcrumb_manager_setting_field_edit_id").val();

    var i = 0;
    while ( jQuery("#breadcrumb_manager_setting_field_post_type_" + i).length > 0 ) {

      var obj = {};
      obj['post_type'] = jQuery("#breadcrumb_manager_setting_field_post_type_" + i).val();
      obj['taxonomy'] = jQuery("#breadcrumb_manager_setting_field_taxonomy_" + i).val();
      obj['term'] = jQuery("#breadcrumb_manager_setting_field_taxonomy_term_" + i).val();
      obj['menu_item_id'] = jQuery("#breadcrumb_manager_setting_field_menu_item_id_" + i).val();
      obj['position_str'] = jQuery("#breadcrumb_manager_setting_field_position_str_" + i).val();

      if ( edit_id !== '' && Number(edit_id) === Number(i) ) {
        var post_type = jQuery("#breadcrumb_manager_setting_field_post_type").val();
        var taxonomy = jQuery("#breadcrumb_manager_setting_field_taxonomy").val();
        var term = jQuery("#breadcrumb_manager_setting_field_taxonomy_term").val();
        var menu_item_id = '';

        var j = 0;
        var position_str = '';
        while (jQuery("#breadcrumb_manager_setting_field_menu_section_" + j).length > 0) {
          if ( jQuery("#breadcrumb_manager_setting_field_menu_section_" + j).val() !== '' && jQuery("#breadcrumb_manager_setting_field_menu_section_" + j).val() !== undefined ) {
            menu_item_id += jQuery("#breadcrumb_manager_setting_field_menu_section_" + j).val() + '|';

            if ( menu_item_id !== '' ) {
              position_str += jQuery("#breadcrumb_manager_setting_field_menu_section_" + j + " option:selected").text() + " / ";
            }
          }

          j++;
        }

        if ( menu_item_id !== '' ) {
          menu_item_id = menu_item_id.slice(0, -1);
        }

        if ( position_str !== '' ) {
          position_str = position_str.slice(0, -3);
        }

        if ( post_type !== '' && menu_item_id !== '' ) {
          var obj = {};
          obj['post_type'] = post_type;
          obj['taxonomy'] = taxonomy;
          obj['term'] = term;
          obj['menu_item_id'] = menu_item_id;
          obj['position_str'] = position_str;
        } else {
          if ( post_type === '' ) {
            alert(__( 'Please select post type', 'cg-breadcrumb-manager-admin' ));
            e.preventDefault();
          } else if ( taxonomy !== '' && term === '' ) {
            alert(__( 'Please select term', 'cg-breadcrumb-manager-admin' ));
            e.preventDefault();
          } else if ( menu_item_id === '' ) {
            alert(__( 'Please select menu section(s)', 'cg-breadcrumb-manager-admin' ));
            e.preventDefault();
          }
        }
      }

      result.push(obj);

      i++;
    }

    if ( edit_id === '' ) {
      var post_type = jQuery("#breadcrumb_manager_setting_field_post_type").val();
      var taxonomy = jQuery("#breadcrumb_manager_setting_field_taxonomy").val();
      var term = jQuery("#breadcrumb_manager_setting_field_taxonomy_term").val();
      var menu_item_id = '';

      var j = 0;
      var position_str = '';
      while (jQuery("#breadcrumb_manager_setting_field_menu_section_" + j).length > 0) {
        if ( jQuery("#breadcrumb_manager_setting_field_menu_section_" + j).val() !== '' && jQuery("#breadcrumb_manager_setting_field_menu_section_" + j).val() !== undefined ) {
          menu_item_id += jQuery("#breadcrumb_manager_setting_field_menu_section_" + j).val() + '|';

          if ( menu_item_id !== '' ) {
            position_str += jQuery("#breadcrumb_manager_setting_field_menu_section_" + j + " option:selected").text() + " / ";
          }
        }

        j++;
      }

      if ( menu_item_id !== '' ) {
        menu_item_id = menu_item_id.slice(0, -1);
      }

      if ( position_str !== '' ) {
        position_str = position_str.slice(0, -3);
      }

      if ( post_type !== '' && menu_item_id !== '' ) {
        var obj = {};
        obj['post_type'] = post_type;
        obj['taxonomy'] = taxonomy;
        obj['term'] = term;
        obj['menu_item_id'] = menu_item_id;
        obj['position_str'] = position_str;
        result.push(obj);
      } else {
        if ( jQuery("#breadcrumb_manager_setting_field_delete_id").val() === '' ) {
          if ( post_type === '' ) {
            alert(__( 'Please select post type', 'cg-breadcrumb-manager-admin' ));
            e.preventDefault();
          } else if ( taxonomy !== '' && term === '' ) {
            alert(__( 'Please select term', 'cg-breadcrumb-manager-admin' ));
            e.preventDefault();
          } else if ( menu_item_id === '' ) {
            alert(__( 'Please select menu section(s)', 'cg-breadcrumb-manager-admin' ));
            e.preventDefault();
          }
        }
      }
    }

    jQuery('#breadcrumb_manager_setting_field').val(JSON.stringify(result));
  });

  get_menu_item_dropdown(0, 0);
});

function get_menu_item_dropdown(elemVal, elemId, callback) {
  var menu_items_arr = JSON.parse(breadcrumb_manager_var.menu_items_arr);

  var nextElemId = Number(elemId) + 1;
  while ( jQuery("#menu_section_item_" + nextElemId).length > 0 ) {
    jQuery("#menu_section_item_" + nextElemId).remove();
    nextElemId++;
  }

  var menu_section_item_count = 0;
  while ( jQuery("#menu_section_item_" + menu_section_item_count).length > 0 ) {
    menu_section_item_count++;
  }

  if ( menu_items_arr[elemVal] !== undefined ) {
    if ( jQuery("#breadcrumb_manager_setting_field_menu_section_" + menu_section_item_count).length > 0 ) {
      var el = jQuery("#breadcrumb_manager_setting_field_menu_section_" + menu_section_item_count);
    } else {
      var menuSectionEl = jQuery(".menu-section");
      var  divEl = document.createElement("DIV");
      divEl.classList.add("menu-section-items");
      var labelEl = document.createElement("LABEL");
      var labelTextEl = document.createTextNode(__( "Choose Menu Section", 'cg-breadcrumb-manager-admin' ));
      labelEl.appendChild(labelTextEl);
      var brEl = document.createElement("BR");
      var selectEl = document.createElement("SELECT");
      selectEl.id = "breadcrumb_manager_setting_field_menu_section_" + menu_section_item_count;
      var optionEl = document.createElement("OPTION");
      optionEl.text = "Select Menu";
      optionEl.value = "";
      selectEl.appendChild(optionEl);
      divEl.appendChild(labelEl);
      divEl.appendChild(brEl);
      divEl.appendChild(selectEl);
      jQuery(".menu-section").append(divEl);
      jQuery("#breadcrumb_manager_setting_field_menu_section_" + menu_section_item_count).on("change", function(e) {
        get_menu_item_dropdown(this.value, menu_section_item_count);
      });

      var el = jQuery("#breadcrumb_manager_setting_field_menu_section_" + menu_section_item_count);
    }

    el.parent().attr("id", "menu_section_item_" + menu_section_item_count);

    var obj = menu_items_arr[elemVal];
    for (var key in obj) {
      if (obj.hasOwnProperty(key)) {
        var val = obj[key];
        el.append(new Option(unescape(val), key));
      }
    }
  }

  update_position_breadcrumb();

  if ( callback !== undefined ) {
    callback();
  }
}

function update_position_breadcrumb() {
  var menu_items_arr = JSON.parse(breadcrumb_manager_var.menu_items_arr);
  var i = 0;
  var position_str = '';
  while (jQuery("#breadcrumb_manager_setting_field_menu_section_" + i).length > 0) {
    var menu_item_id = jQuery("#breadcrumb_manager_setting_field_menu_section_" + i).val();

    if ( menu_item_id !== '' ) {
      position_str += jQuery("#breadcrumb_manager_setting_field_menu_section_" + i + " option:selected").text() + " / ";
    }
    i++;
  }

  if ( position_str !== '' ) {
    position_str = position_str.slice(0, -3);
  }

  jQuery("#position_breadcrumb").html(position_str);
}

function delete_breadcrumb_setting_field(t) {
  if ( confirm("Do you want to delete the setting?") ) {
    jQuery("#breadcrumb_manager_setting_field_delete_id").val(t);
    jQuery("#breadcrumb_manager_setting_field_" + t).remove();

    var i = Number(t) + 1;
    while ( jQuery("#breadcrumb_manager_setting_field_post_type_" + i).length > 0 ) {
      var newIdx = i - 1;
      jQuery("#breadcrumb_manager_setting_field_post_type_" + i).attr("id", "breadcrumb_manager_setting_field_post_type_" + newIdx);
      jQuery("#breadcrumb_manager_setting_field_taxonomy_" + i).attr("id", "breadcrumb_manager_setting_field_taxonomy_" + newIdx);
      jQuery("#breadcrumb_manager_setting_field_taxonomy_term_" + i).attr("id", "breadcrumb_manager_setting_field_taxonomy_term_" + newIdx);
      jQuery("#breadcrumb_manager_setting_field_menu_item_id_" + i).attr("id", "breadcrumb_manager_setting_field_menu_item_id_" + newIdx);
      jQuery("#breadcrumb_manager_setting_field_position_str_" + i).attr("id", "breadcrumb_manager_setting_field_position_str_" + newIdx);

      i++;
    }
    jQuery("#breadcrumb_manager_form").find(':submit').trigger('click');
  }
}

function populate_breadcrumb_setting_field(t) {
  jQuery("#breadcrumb_manager_setting_body").hide();
  var offset = jQuery('#breadcrumb_manager_form').offset().top;
  offset = offset - 40;
  jQuery('html, body').animate({
    scrollTop: offset
  }, 500, function() {
    if ( jQuery("#breadcrumb_manager_cancel_edit_btn").length < 1 ) {
      const cancelValue = __( 'Cancel', 'cg-breadcrumb-manager-admin' );
      jQuery("#breadcrumb_manager_setting_field_edit_id").next().append(`<input type="button" class="button button-secondory" id="breadcrumb_manager_cancel_edit_btn" value="${cancelValue}" />`);

      jQuery("#breadcrumb_manager_cancel_edit_btn").on("click", function() {
        jQuery("#breadcrumb_manager_setting_field_edit_id").val('');
        jQuery("#breadcrumb_manager_setting_body").hide();

        if ( jQuery("#breadcrumb_manager_cancel_edit_btn").length > 0 ) {
          jQuery("#breadcrumb_manager_cancel_edit_btn").remove();
        }

        jQuery("#breadcrumb_section_label").html(__( "Add new setting", 'cg-breadcrumb-manager-admin' ));
        jQuery("#breadcrumb_manager_setting_field_post_type").val('');
        jQuery("#breadcrumb_manager_setting_field_taxonomy").val('');
        jQuery("#breadcrumb_manager_setting_field_taxonomy_term").val('');
        jQuery("#breadcrumb_manager_setting_field_menu_section_0").val('');

        var j = 1;
        while(jQuery("#breadcrumb_manager_setting_field_menu_section_" + j).length > 0) {
          jQuery("#breadcrumb_manager_setting_field_menu_section_" + j).parent().remove();
        }

        update_position_breadcrumb();

        jQuery("#breadcrumb_manager_setting_body").fadeIn("slow");
      });
    }
    jQuery("#breadcrumb_section_label").html("Edit setting");
    jQuery("#breadcrumb_manager_setting_body").fadeIn("slow");
  });

  if ( jQuery("#breadcrumb_manager_setting_field_post_type_" + t).length > 0 ) {
    jQuery("#breadcrumb_manager_setting_field_edit_id").val(t);

    var post_type = jQuery("#breadcrumb_manager_setting_field_post_type_" + t).val();
    var taxonomy = jQuery("#breadcrumb_manager_setting_field_taxonomy_" + t).val();
    var term = jQuery("#breadcrumb_manager_setting_field_taxonomy_term_" + t).val();
    var menu_item_id = jQuery("#breadcrumb_manager_setting_field_menu_item_id_" + t).val();
    var menu_item_id_arr = menu_item_id.split("|")

    jQuery("#breadcrumb_manager_setting_field_post_type").val(post_type);

    get_attached_taxonomy(post_type, function() {

      jQuery("#breadcrumb_manager_setting_field_taxonomy").val(taxonomy);

      get_taxonomy_terms(taxonomy, function() {
        jQuery("#breadcrumb_manager_setting_field_taxonomy_term").val(term);

        if ( menu_item_id_arr.length > 0 ) {
          for(var j=0; j < menu_item_id_arr.length; j++) {
            get_menu_item_dropdown(menu_item_id_arr[j], j, function() {
              jQuery("#breadcrumb_manager_setting_field_menu_section_" + j).val(menu_item_id_arr[j]);
              update_position_breadcrumb();
            });
          }
        }

      });

    });
  }
}
