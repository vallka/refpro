/**
 * 2007-2017 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA    <contact@prestashop.com>
 * @copyright 2007-2017 PrestaShop SA
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
 */

$(function () {
    var options = {
        success: function (responseText, statusText, xhr, $form) {
            showDialog(
                "<div class='ajsmall'>" + extractAjax(responseText) + '</div>',
                ''
            );
            window.location.reload(false);
        },
    };
    $('#setForm').ajaxForm(options);

    $('.ajaxLink').click(function () {
        var action = this.href.substr(this.href.indexOf('#') + 1);

        $.ajax({
            data: {
                ajax: true,
                action: action,
            },
            type: 'POST',
            success: function () {
                showDialog(
                    "<div class='ajsmall'>" + phrases['done'] + '</div>'
                );
                window.location.reload(false);
            },
            error: function () {
                showDialog(
                    "<div class='ajsmall'>" + phrases['error'] + '</div>'
                );
            },
        });
    });

    transferBinder();

    $('#thcb').change(function () {
        if ($(this).attr('checked')) {
            $('#refTable tbody input').attr('checked', true);
        } else {
            $('#refTable tbody input').attr('checked', false);
        }
    });
    var $cc_selected = true;
    $('.group_ids').each(function () {
        $cc_selected = $cc_selected && $(this).attr('checked') == 'checked';
    });
    $('#checkall1').attr('checked', $cc_selected);
    $('#checkall1').change(function () {
        if ($(this).attr('checked')) {
            $('.group_ids').attr('checked', true);
        } else {
            $('.group_ids').attr('checked', false);
        }
    });
    var $cc_selected = true;
    $('.categoryCheckbox').each(function () {
        $cc_selected = $cc_selected && $(this).attr('checked') == 'checked';
    });
    $('#checkall2').attr('checked', $cc_selected);
    $('#checkall2').change(function () {
        if ($(this).attr('checked')) {
            $('.categoryCheckbox').attr('checked', true);
        } else {
            $('.categoryCheckbox').attr('checked', false);
        }
    });

    $('#mailerButton').click(function () {
        var checked = $('#refTable tbody input:checked');
        var _html = '';

        if (checked[0]) {
            var ids = [];
            checked.each(function () {
                ids.push($(this).attr('name').substr(5));
            });
            ids = ids.join(',');
        } else {
            var ids = 'all';
            _html +=
                "<p class='color: yellow;'>" + phrases['mail_warning'] + '</p>';
        }

        $('BODY').on('click', '#mailerForm .sendMail', function () {
            if (
                $('#mailerForm textarea').val() == '' &&
                $('#mailerForm textarea').val() == ''
            ) {
                showDialog(
                    "<div class='ajsmall'>" +
                        phrases['mail_wrong_data'] +
                        '</div>'
                );
                return false;
            }
            $.ajax({
                data: {
                    subject: $("#mailerForm input[name='subject']").val(),
                    text: $('#mailerForm textarea').val(),
                    ajax: true,
                    action: 'send_mail',
                    ids: ids,
                },
                success: function () {
                    showDialog(
                        "<div class='ajsmall'>" +
                            phrases['mail_sent'] +
                            '</div>'
                    );
                },
                error: function () {
                    showDialog(
                        "<div class='ajsmall'>" + phrases['error'] + '</div>'
                    );
                },
                type: 'POST',
            });
        });

        _html +=
            '<div id="mailerForm"> \
			<div class="row"> \
				<label class="control-label col-lg-12" for="m_subject">' +
            phrases['subject'] +
            '</label> \
				<div class="col-lg-12">\
					<input type="text" name="subject" value="" /> \
				</div>\
			</div> \
			<div class="row"> \
			<label class="control-label" for="m_subject">' +
            phrases['text'] +
            '</label> \
			<div class="col-lg-12">\
				<textarea name="text"></textarea> \
			</div>\
			</div> \
			 <div>\
			 	<button class="sendMail btn btn-default">' +
            phrases['send'] +
            '</button>\
			 </div> \
		</div>';
        showDialog(_html, phrases['mailing']);
    });

    $('input[name="remove_state[]"]').on('click', function () {
        var idStatus = parseInt($(this).val());
        if ($('input[name="active_state_' + idStatus + '"]').is(':checked')) {
            $(this).attr('checked', false);
            alert(phrases.add_bonus_alert);
            return false;
        }
    });

    function setDisableGroupFiled() {
        $('input[name="available_ids[]"]').each(function () {
            var idGroup = parseInt($(this).val());
            if ($(this).is(':checked')) {
                $('input[name="percent_' + idGroup + '_0"]').attr(
                    'readonly',
                    false
                );
                $('input[name="percent_' + idGroup + '_1"]').attr(
                    'readonly',
                    false
                );
                $('input[name="percent_' + idGroup + '_2"]').attr(
                    'readonly',
                    false
                );
                $('input[name="percent_' + idGroup + '_3"]').attr(
                    'readonly',
                    false
                );
                $('input[name="percent_' + idGroup + '_4"]').attr(
                    'readonly',
                    false
                );
                $('input[name="percent_' + idGroup + '_5"]').attr(
                    'readonly',
                    false
                );
                $('input[name="percent_' + idGroup + '_6"]').attr(
                    'readonly',
                    false
                );
                $('input[name="percent_' + idGroup + '_7"]').attr(
                    'readonly',
                    false
                );
                $('input[name="percent_' + idGroup + '_8"]').attr(
                    'readonly',
                    false
                );
                $('input[name="percent_' + idGroup + '_9"]').attr(
                    'readonly',
                    false
                );
            } else {
                $('input[name="percent_' + idGroup + '_0"]').attr(
                    'readonly',
                    'readonly'
                );
                $('input[name="percent_' + idGroup + '_1"]').attr(
                    'readonly',
                    'readonly'
                );
                $('input[name="percent_' + idGroup + '_2"]').attr(
                    'readonly',
                    'readonly'
                );
                $('input[name="percent_' + idGroup + '_3"]').attr(
                    'readonly',
                    'readonly'
                );
                $('input[name="percent_' + idGroup + '_4"]').attr(
                    'readonly',
                    'readonly'
                );
                $('input[name="percent_' + idGroup + '_5"]').attr(
                    'readonly',
                    'readonly'
                );
                $('input[name="percent_' + idGroup + '_6"]').attr(
                    'readonly',
                    'readonly'
                );
                $('input[name="percent_' + idGroup + '_7"]').attr(
                    'readonly',
                    'readonly'
                );
                $('input[name="percent_' + idGroup + '_8"]').attr(
                    'readonly',
                    'readonly'
                );
                $('input[name="percent_' + idGroup + '_9"]').attr(
                    'readonly',
                    'readonly'
                );
            }
        });

        return false;
    }

    function setDisableCategoryFiled() {
        $('.categoryCheckbox').each(function () {
            var idCategory = parseInt($(this).attr('data-id-category'));
            if ($(this).is(':checked')) {
                $('input[name="category_percent_' + idCategory + '"]').attr(
                    'readonly',
                    false
                );
            } else {
                $('input[name="category_percent_' + idCategory + '"]').attr(
                    'readonly',
                    'readonly'
                );
            }
        });
        return false;
    }

    $('.categoryCheckbox').on('click', function () {
        setDisableCategoryFiled();
    });
    setDisableCategoryFiled();

    $('input[name="available_ids[]"]').on('click', function () {
        setDisableGroupFiled();
    });
    setDisableGroupFiled();

    $('.clicbleChecking').on('click', function () {
        var idStatus = parseInt($(this).val());

        if ($('#removing_bonus_' + idStatus).is(':checked')) {
            $(this).attr('checked', false);
            alert(phrases.remove_bonus_alert);
            return false;
        }
    });

    $('#involvedEveryoneButton').on('click', function () {
        if (confirm(phrases['sure'])) {
            $('#everyone_involved').trigger('click');
        }
    });

    $('.showFormLoadImage').on('click', function () {
        $('.stage_form_load_image, .form_load_image').fadeIn(500);
        $('.form_load_image').css({
            top: $(document).scrollTop() + 50 + 'px',
        });
    });
    $('.showFormLoadImage2').on('click', function () {
        $('.stage_form_load_image, .form_load_image2').fadeIn(500);
        $('.form_load_image2').css({
            top: $(document).scrollTop() + 50 + 'px',
        });
    });
    $(
        '.stage_form_load_image, .close_form_load_image, .form_load_modal_close'
    ).on('click', function () {
        $(
            '.stage_form_load_image, .form_load_image, .form_load_image2'
        ).fadeOut(500);
    });

    var tab_container = new TabContainer('.tab_container');
    tab_container.init();

    $('[name="has_voucher"]')
        .on('change', function () {
            $('.voucher_percent, .voucher_amount').hide();
            if ($(this).val() == 'percent') {
                $('.voucher_percent').show();
            } else if ($(this).val() == 'amount') {
                $('.voucher_amount').show();
            }
        })
        .trigger('change');

    // $('.ref_action_edit').on('click', function() {
    $(document).on('click', '.ref_action_edit', function () {
        var this_row = $(this).parents().eq(3);
        var customer_id = this_row.find('.ref_id').eq(0).text().trim();
        var sponsor_id = this_row.find('.ref_sponsor').eq(0).text().trim();

        var new_sponsor_id = prompt(phrases['new_id'], sponsor_id);

        if (
            new_sponsor_id == null ||
            new_sponsor_id == '' ||
            new_sponsor_id == customer_id
        ) {
            showDialog("<div class='ajsmall'>" + phrases['error'] + '</div>');
        } else {
            $.ajax({
                data: {
                    ajax: true,
                    action: 'edit_ref',
                    id: customer_id,
                    sponsor: new_sponsor_id,
                },
                type: 'POST',
                success: function () {
                    this_row.find('.ref_sponsor').text(new_sponsor_id);
                    showDialog(
                        "<div class='ajsmall'>" + phrases['done'] + '</div>'
                    );
                },
                error: function () {
                    showDialog(
                        "<div class='ajsmall'>" + phrases['error'] + '</div>'
                    );
                },
            });
        }
    });
    // $('.ref_action_delete').on('click', function() {
    $(document).on('click', '.ref_action_delete', function () {
        var this_row = $(this).parents().eq(3);

        $.ajax({
            data: {
                ajax: true,
                action: 'delete_ref',
                id: this_row.find('.ref_id').eq(0).text().trim(),
            },
            type: 'POST',
            success: function () {
                this_row.fadeOut(500);
                showDialog(
                    "<div class='ajsmall'>" + phrases['done'] + '</div>'
                );
            },
            error: function () {
                showDialog(
                    "<div class='ajsmall'>" + phrases['error'] + '</div>'
                );
            },
        });
    });
    //$('.ref_action_disable').on('click', function() {
    $(document).on('click', '.ref_action_disable', function () {
        var this_row = $(this).parents().eq(3);

        $.ajax({
            data: {
                ajax: true,
                action: 'disable_ref',
                id: this_row.find('.ref_id').eq(0).text().trim(),
            },
            type: 'POST',
            success: function () {
                if (
                    this_row.find('.ref_action_disable i').text() ==
                        'play_arrow' ||
                    this_row.find('.ref_action_disable .icon-play').length
                ) {
                    this_row.find('td').css('background-color', 'inherit');
                    this_row
                        .find('.ref_action_disable .material-icons')
                        .text('pause');
                    this_row
                        .find('.ref_action_disable .icon-play')
                        .removeClass('icon-play')
                        .addClass('icon-pause');
                } else {
                    this_row.find('td').css('background-color', '#ddd');
                    this_row
                        .find('.ref_action_disable .material-icons')
                        .text('play_arrow');
                    this_row
                        .find('.ref_action_disable .icon-pause')
                        .removeClass('icon-pause')
                        .addClass('icon-play');
                }
                showDialog(
                    "<div class='ajsmall'>" + phrases['done'] + '</div>'
                );
            },
            error: function () {
                showDialog(
                    "<div class='ajsmall'>" + phrases['error'] + '</div>'
                );
            },
        });
    });
});

function transferBinder() {
    $('.transfer')
        .off()
        .click(function () {
            var clicked = $(this);
            var user_wallets = $(this).siblings('.goZeroData').html();
            var buttons = {};
            buttons[phrases['set_zero']] = function () {
                $.ajax({
                    data: {
                        ajax: true,
                        action: 'zero_balance',
                        id: clicked
                            .parent()
                            .parent()
                            .find('input')
                            .attr('name')
                            .substr(5),
                    },
                    type: 'POST',
                    success: function () {
                        showDialog(
                            "<div class='ajsmall'>" +
                                phrases['done'] +
                                '!</div>'
                        );
                        document.location.reload(true);
                    },
                    error: function () {
                        showDialog(
                            "<div class='ajsmall'>" +
                                phrases['error'] +
                                '</div>'
                        );
                    },
                });
            };

            showDialog(user_wallets, phrases['withdraw'], buttons);
        });
}

$(function () {
    var _all = $('.category_tree_js_parent');

    _all.each(function () {
        var _this = $(this);
        var cat_id = parseInt(_this.data('cat_id'));
        var id_parent = parseInt(_this.data('id_parent'));
        var level_depth = parseInt(_this.data('level_depth'));
        var _expander = _this.find('span.expander');

        var _parent = $(
            '#treeJsJsox .category_tree_js_parent[data-cat_id="' +
                id_parent +
                '"]'
        );
        if (_parent.length) {
            var treeEl = $(
                '#treeJsJsox .category_tree_js_parent[data-cat_id="' +
                    cat_id +
                    '"]'
            );
            _parent.append(treeEl.clone(true));
            treeEl.remove();
        } else {
            $('#treeJsJsox').append(treeEl);
        }
    });

    $('span.expander').each(function () {
        var _this = $(this);
        var _treeEl = _this.closest('.category_tree_js_parent');
        var _childs = _treeEl.find('div.category_tree_js_parent');
        if (_childs.length) {
            _this.click(function () {
                toggleCatTreeEl(_treeEl);
            });
        } else {
            _this.html('&nbsp;').css('cursor', 'auto');
        }
    });

    $('#expandAll').click(function () {
        $('.category_tree_js_parent.tree_el_not_expanded')
            .find('span.expander')
            .click();
    });
    $('#collapseAll').click(function () {
        $('.category_tree_js_parent:not(.tree_el_not_expanded)')
            .find('span.expander')
            .click();
    });

    $('.adminrefpro .category_tree_js_parent[data-level_depth="2"]')
        .find('span.expander')
        .click();
});

function toggleCatTreeEl(_el) {
    var _expander = _el.find('.expander').eq(0);
    if (!isTreeElNotExpanded(_el)) {
        _expander.html('+');
        _el.addClass('tree_el_not_expanded');
        _el.find('> div.category_tree_js_parent').slideUp(150);
    } else {
        _expander.html('-');
        _el.removeClass('tree_el_not_expanded');
        _el.find('> div.category_tree_js_parent').slideDown(150);
    }
}
function isTreeElNotExpanded(_el) {
    if (_el.hasClass('tree_el_not_expanded')) {
        return true;
    } else {
        return false;
    }
}
