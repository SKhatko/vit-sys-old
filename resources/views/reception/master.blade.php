@extends('app')

@section('layout')

    <!-- Include header -->
    @include('layout/header')

    <main class="main">

        <!-- Include sidebar -->
    @include('layout/sidebar')

    <!-- Include content -->
        <section class="content">
            @yield('content')
        </section>

    </main>

@stop

@section('script')

    <script>

        // TODO all remove

        function getMinutes(hour, min) {
            return parseInt(hour) * 60 + parseInt(min);
        }


        function autocompleteFormatResult(name, statusName, phone, mobile) {

            var img = '';
            if (statusName) {
                img = '<img src="{{ asset('img/icons/32/client-status') }}/' + statusName + '.png" alt="' + statusName + '" />';
            }

            var result = '<div>' + name + ' <i class="pull-right">' + img + '</i></div>';
            result += '<div><span>' + phone + '</span><span class="pull-right">' + mobile + '</span></div>';
            return result;
        }

        function resetClientInputs() {
            $("input[name='client_id']").val("");
            $(".previous-reservations-holder").html("0");
            $("input[name='client_status_id'][value='1']").prop('checked', true);
            $(".form-control[name='sticky_note']").val("");
            window.phoneClientData = null;
            window.mobileClientData = null;
        }

        $(function () {

            var companyXhr;
            var autocompleteXhr;
            var phoneXhr;

            $('.autocomplete-company-name').on('change', function () {
                if ($(this).val() == '') {
                    $("input[name='company_id']").val("");
                }
            });

            $('.autocomplete-company-name').autocomplete({
                lookup: function (query, done) {

                    $(".autocomplete-company-name-loader").show();
                    $("input[name='company_id']").val("");

                    var emptySuggestions = {
                        suggestions: []
                    };

                    if (companyXhr && companyXhr.readyState != 4) {
                        companyXhr.abort();
                    }

                    companyXhr = $.ajax({
                        method: "GET",
                        url: "{{ action('CompaniesController@autocompleteName') }}/" + query
                    }).done(function (data) {
                        if (data && data['suggestions']) {
                            if (data['suggestions'].length) {
                                done(data);
                            }
                            else {
                                done(emptySuggestions);
                            }
                        }
                        else {
                            done(emptySuggestions);
                        }

                        $(".autocomplete-company-name-loader").hide();
                    });

                },
                onSelect: function (suggestion) {
                    $("input[name='company_id']").val(suggestion.data);
                },
                formatResult: function (suggestion, currValue) {
                    var regEx = new RegExp(currValue, "ig");
                    var valString = suggestion.value.replace(regEx, "<strong>" + currValue + "</strong>");

                    return valString;
                }
            });

            $('.autocomplete-client-last-name').autocomplete({
                triggerSelectOnValidInput: false,
                lookup: function (query, done) {
                    // Do ajax call or lookup locally, when done,
                    // call the callback and pass your results:
                    $(".autocomplete-last-name-loader").show();

                    var emptySuggestions = {
                        suggestions: []
                    };

                    if (autocompleteXhr && autocompleteXhr.readyState != 4) {
                        autocompleteXhr.abort();
                    }

                    autocompleteXhr = $.ajax({
                        method: "GET",
                        url: "{{ action('ClientsController@autocompleteLastName') }}/" + query
                    }).done(function (data) {
                        if (data && data['suggestions']) {
                            if (data['suggestions'].length) {
                                done(data);
                            }
                            else {
                                done(emptySuggestions);
                            }
                        }
                        else {
                            done(emptySuggestions);
                        }

                        $(".autocomplete-last-name-loader").hide();
                    });

                },
                onSelect: function (suggestion) {
                    //add client ID to input
                    //if ($("input[name='phone']").val() == "" && $("input[name='mobile']").val() == "") {

                    //if (suggestion.extra.phone) {
                    $("input[name='phone']").val(suggestion.extra.phone);
                    //}
                    //if (suggestion.extra.first_name) {
                    $("input[name='first_name']").val(suggestion.extra.first_name);
                    //}
                    //if (suggestion.extra.mobile) {
                    $("input[name='mobile']").val(suggestion.extra.mobile);
                    //}

                    $("input[name='client_id']").val(suggestion.data);
                    $(".previous-reservations-holder").html(suggestion.extra.reservations);
                    $("input[name='client_status_id'][value='" + suggestion.extra.status_id + "']").prop('checked', true);
                    $(".form-control[name='sticky_note']").val(suggestion.extra.sticky);

                    if (suggestion.extra.email && $("input[name='email']").val() == '') {
                        $("input[name='email']").val(suggestion.extra.email);
                    }

                    if (suggestion.extra.gender && $("[name='gender']").val() == '') {
                        $("[name='gender']").val(suggestion.extra.gender);
                    }

                    if (suggestion.extra.phone) {
                        window.phoneClientData = suggestion;
                    }
                },
                formatResult: function (suggestion, currValue) {
                    var regEx = new RegExp(currValue, "ig");
                    var valString = suggestion.value.replace(regEx, "<strong>" + currValue + "</strong>");

                    return autocompleteFormatResult(suggestion.extra.first_name + ' ' + valString,
                        suggestion.extra.status_name,
                        suggestion.extra.phone,
                        suggestion.extra.mobile);
                }
            });

            $('.autocomplete-client-phone').autocomplete({
                triggerSelectOnValidInput: true,
                lookup: function (query, done) {
                    // Do ajax call or lookup locally, when done,
                    // call the callback and pass your results:

                    $(".autocomplete-phone-loader").show();

                    if ($("input[name='mobile']").val() == "" || window.mobileClientData == null) {
                        resetClientInputs();
                    }

                    var emptySuggestions = {
                        suggestions: []
                    };

                    if (phoneXhr && phoneXhr.readyState != 4) {
                        phoneXhr.abort();
                    }

                    phoneXhr = $.ajax({
                        method: "GET",
                        url: "{{ action('ClientsController@autocompletePhone') }}/" + query
                    }).done(function (data) {
                        if (data && data['suggestions']) {
                            if (data['suggestions'].length) {
                                done(data);
                            }
                            else {
                                done(emptySuggestions);
                            }
                        }
                        else {
                            done(emptySuggestions);
                        }

                        $(".autocomplete-name-loader").hide();
                    });

                },
                onSelect: function (suggestion) {
                    //add client ID to input
                    if ($("input[name='mobile']").val() == "" || window.mobileClientData == null) {
                        $("input[name='client_id']").val(suggestion.data);
                        $(".previous-reservations-holder").html(suggestion.extra.reservations);
                        $("input[name='client_status_id'][value='" + suggestion.extra.status_id + "']").prop('checked', true);

                        if ($("input[name='last_name']").val() == "") {
                            $("input[name='last_name']").val(suggestion.extra.last_name);
                        }
                        if ($("input[name='first_name']").val() == "") {
                            $("input[name='first_name']").val(suggestion.extra.first_name);
                        }

                        //mobile is empty
                        $("input[name='mobile']").val(suggestion.extra.mobile);
                        $(".form-control[name='sticky_note']").val(suggestion.extra.sticky);

                        if (suggestion.extra.email && $("input[name='email']").val() == '') {
                            $("input[name='email']").val(suggestion.extra.email);
                        }

                        if (suggestion.extra.gender && $("[name='gender']").val() == '') {
                            $("[name='gender']").val(suggestion.extra.gender);
                        }

                        window.phoneClientData = suggestion;
                    }
                },
                formatResult: function (suggestion, currValue) {
                    var regEx = new RegExp(currValue, "ig");
                    var valString = suggestion.value.replace(regEx, "<strong>" + currValue + "</strong>");

                    return autocompleteFormatResult(suggestion.extra.first_name + ' ' + suggestion.extra.last_name,
                        suggestion.extra.status_name,
                        valString,
                        suggestion.extra.mobile);

                    //return valString+' <i>'+suggestion.extra.name+'</i>';
                }
            });

            $('.autocomplete-client-mobile').autocomplete({
                triggerSelectOnValidInput: true,
                lookup: function (query, done) {
                    // Do ajax call or lookup locally, when done,
                    // call the callback and pass your results:
                    $(".autocomplete-mobile-loader").show();

                    if ($("input[name='phone']").val() == "") {
                        resetClientInputs();
                    }
                    else {
                        var phoneNum = $("input[name='phone']").val();
                        var selectedClientId = $("input[name='client_id']");
                        if (window.phoneClientData != null && selectedClientId != window.phoneClientData.data) {
                            var suggestion = window.phoneClientData;
                            $("input[name='client_id']").val(suggestion.data);
                            $(".previous-reservations-holder").html(suggestion.extra.reservations);
                            $("input[name='client_status_id'][value='" + suggestion.extra.status_id + "']").prop('checked', true);

                            if ($("input[name='last_name']").val() == "") {
                                $("input[name='last_name']").val(suggestion.extra.last_name);
                            }
                            if ($("input[name='first_name']").val() == "") {
                                $("input[name='first_name']").val(suggestion.extra.first_name);
                            }

                            //mobile is empty
                            //$("input[name='mobile']").val(suggestion.extra.mobile);
                            $(".form-control[name='sticky_note']").val(suggestion.extra.sticky);
                        }
                    }

                    var emptySuggestions = {
                        suggestions: []
                    };

                    if (phoneXhr && phoneXhr.readyState != 4) {
                        phoneXhr.abort();
                    }

                    phoneXhr = $.ajax({
                        method: "GET",
                        url: "{{ action('ClientsController@autocompleteMobile') }}/" + query
                    }).done(function (data) {
                        if (data && data['suggestions']) {
                            if (data['suggestions'].length) {
                                done(data);
                            }
                            else {
                                done(emptySuggestions);
                            }
                        }
                        else {
                            done(emptySuggestions);
                        }

                        $(".autocomplete-name-loader").hide();
                    });

                },
                onSelect: function (suggestion) {

                    //add client ID to input
                    $("input[name='client_id']").val(suggestion.data);
                    $(".previous-reservations-holder").html(suggestion.extra.reservations);
                    $("input[name='client_status_id'][value='" + suggestion.extra.status_id + "']").prop('checked', true);
                    if ($("input[name='last_name']").val() == "") {
                        $("input[name='last_name']").val(suggestion.extra.last_name);
                    }
                    if ($("input[name='first_name']").val() == "") {
                        $("input[name='first_name']").val(suggestion.extra.first_name);
                    }
                    if ($("input[name='phone']").val() == "") {
                        $("input[name='phone']").val(suggestion.extra.phone);
                    }
                    $(".form-control[name='sticky_note']").val(suggestion.extra.sticky);

                    if (suggestion.extra.email && $("input[name='email']").val() == '') {
                        $("input[name='email']").val(suggestion.extra.email);
                    }

                    if (suggestion.extra.gender && $("[name='gender']").val() == '') {
                        $("[name='gender']").val(suggestion.extra.gender);
                    }

                    window.mobileClientData = suggestion;

                },
                formatResult: function (suggestion, currValue) {
                    var regEx = new RegExp(currValue, "ig");
                    var valString = suggestion.value.replace(regEx, "<strong>" + currValue + "</strong>");

                    return autocompleteFormatResult(suggestion.extra.first_name + ' ' + suggestion.extra.last_name,
                        suggestion.extra.status_name,
                        suggestion.extra.phone,
                        valString);

                    //return valString+' <i>'+suggestion.extra.phone+'</i><br><i>'+suggestion.extra.first_name+' '+suggestion.extra.last_name+'</i>';
                }
            });

        });
    </script>

    {{--        <script src="{{ asset('/js/bootstrap-datepicker.js?v=1.1') }}" type="text/javascript"></script>--}}
    {{--        <script src="{{ asset('/js/jquery.autocomplete.js') }}" type="text/javascript"></script>--}}

@stop