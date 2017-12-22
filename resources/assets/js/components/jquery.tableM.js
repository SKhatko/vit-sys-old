(function ($) {

        $.fn.tablesM = function (options) {

            // Static elements
            let objectLabelInput = $('#label'),
                objectWidthInput = $('#width'),
                objectHeightInput = $('#height'),
                objectBorderRadiusInput = $('#border-radius'),
                addTableModal = $('.js-restaurant-plan__modal-create'),
                editTableModal = $('.js-restaurant-plan__modal-edit'),
                $tooltips = $('[data-toggle="tooltip"]'),
                addTableButton = $('.create-table-button'),
                updateTableButton = $('.update-table-button'),
                objectTypeTitle = $('.panel-heading'),
                objectPropertiesBlock = $('.js-restaurant-plan__tools-properties'),
                saveTablePlanBlock = $('.js-restaurant-plan__tools-properties-save'),
                TablePlanObjectsHiddenInput = $('.js-table-plan-objects'),
                sectionMap = $('.section-map'),
                sectionMapProportion = 1.375,
                baseH, baseW, availableHeight, availableWidth, oldWidth, oldHeight;

            let settings = $.extend({
                'tableViewFlag': false,
                'tablePlanObjects': {},
                'tablePlanId': 0,
                'defaultX': 0,
                'defaultY': 0,
                'tableWidth': 40,
                'tableHeight': 20,
                'wallWidth': 40,
                'wallHeight': 5,
                'plantWidth': 10,
                'plantHeight': 10,
                'pillarWidth': Math.round(8 / sectionMapProportion),
                'pillarHeight': 8,
                'tableBorderRadius': 0,
                'wallBorderRadius': 0,
                'plantBorderRadius': 0,
                'pillarBorderRadius': 50,
                'onDragBackgroundColor': '#ddd',
                'defaultBackgroundColor': '#decfb1',
                'defaultPersonsNumber': 2,
                'tableActive': null,
                'tablesReserved': null,
                'deleteObjectMsg': '',

                'tablePicked': function (tableId) {
                }
            }, options);

            let obj = this;
            let tables = [];

            $tooltips.tooltip('disable');

            // Object constructor

            function Object(id, sectionId, type, x, y, object_num, persons_num) {
                this.id = id;
                this.section_id = sectionId;
                this.table_plan_id = +settings.tablePlanId;
                this.type = type;
                this.x = x || settings.defaultX;
                this.y = y || settings.defaultY;
                this.width = settings[type + 'Width'];
                this.height = settings[type + 'Height'];
                this.border_radius = settings[type + 'BorderRadius'];
                this.label = '';
                this.object_num = object_num || 0;
                this.persons_num = persons_num || 0;
            }

            // Initialisation function
            obj.init = function () {
                $(this).find('.section-content').each(function () {
                    if (settings.tablePlanObjects) {
                        let sectionId = $(this).data('section-id');
                        renderSectionMap(sectionId, $(this));
                    }
                });
                addEventListeners();
                setMapSizing();
            };

            // Initialisation in table view (reception)
            obj.draw = function () {
                $(this).find('.section-content').each(function () {
                    if (settings.tablePlanObjects) {
                        let sectionId = $(this).data('section-id');
                        renderSectionMap(sectionId, $(this));
                    }
                });
            };

            return obj;

            // Set map and right section sizing scale
            function setMapSizing() {
                if (sectionMap.offset().left > 45) {
                    // desktop size section map (with sidebar)
                    availableHeight = $(window).height() - sectionMap.offset().top - 20;
                    availableWidth = $(window).width() - sectionMap.offset().left;

                    if (availableHeight * sectionMapProportion > availableWidth) {
                        baseW = availableWidth;
                        baseH = Math.round(baseW / sectionMapProportion);
                    } else {
                        baseH = availableHeight;
                        baseW = Math.round(baseH * sectionMapProportion);
                    }

                } else {
                    // tablet size section map (no sidebar)
                    availableHeight = $(window).height() - sectionMap.offset().top - 20;
                    availableWidth = $(window).width();

                    if (availableHeight * sectionMapProportion > availableWidth) {
                        baseW = availableWidth - 45;
                        baseH = baseW / sectionMapProportion;
                    } else {
                        baseH = availableHeight;
                        baseW = baseH * sectionMapProportion;
                    }
                }

                baseH = Math.round(baseH);
                baseW = Math.round(baseW);

                if (baseH < 400) {
                    baseH = 400;
                }
                if (baseW < 550) {
                    baseW = 550;
                }
                sectionMap.height(baseH);
                sectionMap.width(baseW);
            }

            // Insert section map with objects, render it on page
            function renderSectionMap(sectionId, section) {

                let sectionObjects = settings.tablePlanObjects[sectionId];
                section.find('.section-map').html('');

                if (sectionObjects && sectionObjects.length) {
                    console.log(sectionObjects);

                    let sectionObjectsCount = sectionObjects.length - 1;
                    for (let i = sectionObjectsCount; i >= 0; i--) {
                        renderObject(sectionObjects[i], section, false);
                    }
                }
            }

            function splitTableIntoMultiple(tableStr) {
                if (tableStr == null) {
                    return null;
                }

                let tables = tableStr.split('+');
                return tables;
            }


            function splitArrayOfTables(tablesArray) {

                if (tablesArray == null) return null;

                let splitArray = [];
                for (let key in tablesArray) {

                    let subTables = splitTableIntoMultiple(tablesArray[key]);

                    for (let subKey in subTables) {
                        splitArray.push(subTables[subKey]);
                    }
                }

                return splitArray;
            }

            function getClassesForTable(tableObject) {

                if (tableObject == null || !tableObject.object_num || tableObject.object_num == "" || tableObject.object_num == 0 || (!settings.tableActive && !settings.tablesReserved)) {
                    return "";
                }

                let classes = "";
                let tables = splitTableIntoMultiple(tableObject.object_num);

                let activeTables = splitTableIntoMultiple(settings.tableActive);
                let reservedTables = splitArrayOfTables(settings.tablesReserved);

                for (let tableKey in tables) {

                    if (activeTables != null) {
                        for (let activeTableKey in activeTables) {

                            if (activeTables[activeTableKey] == tables[tableKey]) {
                                classes += " active";
                                break;
                            }
                        }
                    }

                    if (reservedTables != null) {
                        for (let reservedTableKey in reservedTables) {
                            if (reservedTables[reservedTableKey] == tables[tableKey]) {
                                classes += " reserved";
                                break;
                            }
                        }
                    }
                }

                return classes;
            }


            // Insert object to section, render shape and sizes
            function renderObject(sectionObject, section, editProperties) {

                let sectionObjectType = sectionObject.type;
                let sectionObjectId = sectionObject.id;
                let sectionId = sectionObject.section_id;
                let sectionObjects = settings.tablePlanObjects[sectionId];
                let activeClass = sectionObjectType === 'table' ? getClassesForTable(sectionObject) : '';

                let sectionObjectHtml = '<div class="tableM-object tableM-object-' + sectionObjectType + activeClass + ' object-' + sectionObjectId + '" title="' + sectionObject.label + '" data-object-id="' + sectionObjectId + '" data-object-type="' + sectionObjectType + '">';

                if (sectionObjectType === 'table') {
                    tables.push(sectionObject.object_num);

                    sectionObjectHtml += '<span class="tableM-info">';
                    sectionObjectHtml += '<svg class="tableM-table-icon"><use xlink:href="#icon-plan-table"></use></svg><span class="tableM-number" data-object-num="' + sectionObject.object_num + '">' + sectionObject.object_num + '</span>';
                    sectionObjectHtml += '<svg class="tableM-person-icon"><use xlink:href="#icon-plan-person"></use></svg><span class="tableM-persons">' + sectionObject.persons_num + '</span></span>';
                    sectionObjectHtml += '<span class="tableM-label" title="' + sectionObject.label + '">' + sectionObject.label + '</span>';

                    if (!settings.tableViewFlag) {
                        sectionObjectHtml += '<svg class="tableM-edit-icon hidden-print" data-object-id="' + sectionObjectId + '"><use xlink:href="#icon-edit"></use></svg>';
                    }
                }

                if (!settings.tableViewFlag) {
                    sectionObjectHtml += '<svg class="tableM-delete-icon hidden-print " data-object-id="' + sectionObjectId + '"><use xlink:href="#icon-cross"></use></svg>';
                }

                sectionObjectHtml += '</div>';

                section.find('.section-map').append(sectionObjectHtml);

                let jObject = $(".object-" + sectionObjectId);
                jObject.css({
                    'top': sectionObject.x + '%',
                    "left": sectionObject.y + "%",
                    "width": sectionObject.width + "%",
                    "height": sectionObject.height + "%",
                    "border-radius": sectionObject.border_radius + "%"
                });

                jObject.find('.tableM-delete-icon').on('click', function () {
                    if (confirm(settings.deleteObjectMsg)) {
                        let thisObject = $(this).parent();
                        let thisObjectId = thisObject.data('object-id');
                        let objectNumber = thisObject.find('.tableM-number').data('object-num');
                        let index = getIndex(sectionObjects, thisObjectId);

                        if (tables.indexOf(objectNumber) != -1) {
                            tables.splice(tables.indexOf(objectNumber), 1);
                        }
                        if (index > -1) {
                            sectionObjects.splice(index, 1);
                            thisObject.remove();
                            setOnBeforeUnload();
                        }
                    }
                });

                jObject.find('.tableM-edit-icon').on('click', function () {
                    $tooltips.tooltip('hide');
                    editTableModal.modal('show');

                    let object = $(this);
                    let objectNumberInput = editTableModal.find('.object-num-input');
                    let personsNumberInput = editTableModal.find('.persons-num-input');
                    let oldObjectNumber = $(this).closest('.tableM-object').find('.tableM-number').data('object-num');

                    objectNumberInput.val(oldObjectNumber);
                    personsNumberInput.val($(this).closest('.tableM-object').find('.tableM-persons').text());

                    updateTableButton.off('click');
                    updateTableButton.on('click', function () {
                        let objectNumber = objectNumberInput.val().replace(/\+/g, "");
                        let personsNumber = +personsNumberInput.val();
                        let personsValidation = true;
                        let tableValidation = true;

                        if (!personsNumber || personsNumber == 0) {
                            personsNumberInput.tooltip('show');
                            personsValidation = false;
                        } else {
                            personsNumberInput.tooltip('hide');
                            personsValidation = true
                        }

                        if (!objectNumber || tables.indexOf(objectNumber) != -1) {
                            if (objectNumber == oldObjectNumber) {
                                tables.splice(tables.indexOf(objectNumber), 1);
                                objectNumberInput.tooltip('hide');
                                tableValidation = true;
                            } else {
                                objectNumberInput.tooltip('show');
                                tableValidation = false;
                            }
                        } else {
                            objectNumberInput.tooltip('hide');
                            tableValidation = true;
                        }

                        if (personsValidation && tableValidation) {
                            // store object
                            jObject = object.closest('.tableM-object');
                            let objectId = jObject.data('object-id');
                            let sectionId = jObject.closest('.section-content').data('section-id');
                            let tableIndex = getIndex(settings.tablePlanObjects[sectionId], objectId);
                            let objectProperties = settings.tablePlanObjects[sectionId][tableIndex];
                            let jObjectNumber = jObject.find('.tableM-number');

                            tables.push(objectNumber);


                            jObjectNumber.text(objectNumber);
                            jObjectNumber.data('object-num', objectNumber);
                            jObject.find('.tableM-persons').text(personsNumber);

                            objectProperties.object_num = objectNumber;
                            objectProperties.persons_num = personsNumber;

                            editTableModal.modal('hide');
                            setOnBeforeUnload();
                        }
                    });
                });


                if (settings.tableViewFlag) {
                    jObject.on('click', function () {
                        let objectNumber = $(this).find('.tableM-number').data('object-num');
                        if (objectNumber || objectNumber == 0) {
                            settings.tablePicked(objectNumber);
                        }
                    });
                }

                if (!settings.tableViewFlag) {
                    jObject.draggable({
                        containment: "parent",
                        grid: [5, 5],
                        stop: function () {
                            setOnBeforeUnload();

                            let index = getIndex(sectionObjects, $(this).data('object-id'));
                            if (index > -1) {
                                sectionObjects[index].x = Math.round($(this).position().top / baseH * 100);
                                sectionObjects[index].y = Math.round($(this).position().left / baseW * 100);
                            }
                        }
                    });

                    if (sectionObjectType === 'pillar' || sectionObjectType === 'plant') {
                        jObject.resizable({
                            containment: "parent",
                            grid: 5,
                            aspectRatio: 1,
                            minHeight: 25,
                            minWidth: 25,
                            maxWidth: baseW - this.offsetLeft,
                            maxHeight: baseH - this.offsetTop,
                            stop: function (e, resizedObject) {
                                let index = getIndex(sectionObjects, $(this).data('object-id'));
                                if (index > -1) {
                                    sectionObjects[index].width = Math.round(resizedObject.size.width / baseW * 100);
                                    sectionObjects[index].height = Math.round(resizedObject.size.height / baseH * 100);
                                }
                                editObjectsProperties(resizedObject.element);
                                setOnBeforeUnload();
                            }
                        });
                    } else {
                        jObject.resizable({
                            containment: "parent",
                            grid: 5,
                            minHeight: 25,
                            minWidth: 25,
                            maxWidth: baseW - this.offsetLeft,
                            maxHeight: baseH - this.offsetTop,
                            stop: function (e, resizedObject) {
                                let index = getIndex(sectionObjects, $(this).data('object-id'));
                                if (index > -1) {
                                    sectionObjects[index].width = Math.round(resizedObject.size.width / baseW * 100);
                                    sectionObjects[index].height = Math.round(resizedObject.size.height / baseH * 100);
                                }
                                editObjectsProperties(resizedObject.element);
                                setOnBeforeUnload();
                            }
                        });
                    }

                }

                if (editProperties) {
                    setOnBeforeUnload();
                    editObjectsProperties(jObject);
                }
            }

            // Set listeners
            function addEventListeners() {

                let createObjectButton = $('.js-restaurant-plan__tools-button.active');
                let sectionMap = $('.section-map');

                // Activate tooltips
                // tooltips.tooltip({placement: 'left', trigger: 'manual'});

                // Hide active objects on section change
                $('a[data-toggle="tab"]').on('shown.bs.tab', function () {
                    objectPropertiesBlock.removeClass('active');
                    $('.tableM-object').removeClass('active');
                });

                // Store objects in hidden input on submit
                $(".js-restaurant-plan__tools-save--submit").closest('form').submit(function () {
                    if (settings.tablePlanObjects) {
                        saveObjectsToInput();
                    }
                    unsetOnBeforeUnload();
                });

                // Add object to section by click on one of objects type

                createObjectButton.click(function (e) {
                    e.preventDefault();
                    let button = $(this);
                    button.removeClass('active');

                    setTimeout(function () {
                        button.addClass('active');
                    }, 2000);

                    let section = $('.section-content.active');
                    let sectionId = section.data('section-id');
                    let objectType = button.data('object-type');

                    if (objectType === 'table') {
                        addTableModal.modal('show');
                        let objectNumberInput = addTableModal.find('.object-num-input');
                        let personsNumberInput = addTableModal.find('.persons-num-input');

                        objectNumberInput.val(1);
                        personsNumberInput.val(settings.defaultPersonsNumber);

                        addTableButton.off('click');
                        addTableButton.on('click', function () {
                            $tooltips.tooltip('enable');
                            let objectNumber = objectNumberInput.val().replace(/\+/g, "");
                            let personsNumber = +personsNumberInput.val();
                            let personsValidation = true;

                            let tableValidation = true;
                            if (!personsNumber || personsNumber == 0) {
                                personsNumberInput.tooltip('show');
                                personsValidation = false;
                            } else {
                                personsNumberInput.tooltip('hide');
                                personsValidation = true
                            }

                            if (!objectNumber || tables.indexOf(objectNumber) != -1) {
                                objectNumberInput.tooltip('show');
                                tableValidation = false;
                            } else {
                                objectNumberInput.tooltip('hide');
                                tableValidation = true;
                            }

                            if (personsValidation && tableValidation) {
                                let newObject = new Object(Date.now(), sectionId, objectType, null, null, objectNumber, personsNumber);
                                addObject(newObject, sectionId, section);
                                addTableModal.modal('hide');
                            }
                        });
                    } else {
                        let newObject = new Object(Date.now(), sectionId, objectType, null, null);
                        addObject(newObject, sectionId, section);
                    }
                });

                // Pass object type on drag
                createObjectButton.on("dragstart", function (event) {
                    event.originalEvent.dataTransfer.setData("text/plain", $(event.target).data('object-type'));
                });

                // Edit object on mousedown
                sectionMap.on('mousedown', '.tableM-object', function () {
                    editObjectsProperties($(this));
                });

                // Deselect object upon section click
                sectionMap.on('click', function (e) {
                    if (e.target === this) {
                        objectPropertiesBlock.removeClass('active');
                        $('.tableM-object').removeClass('active');
                        $('.restaurant-plan__tools-properties-input input').each((i, e) => {
                            $(e).val("");
                        });
                    }
                });

                // Change section background on dragenter
                sectionMap.on("dragenter", function () {
                    $(this).css('background-color', settings.onDragBackgroundColor);
                });

                // Change background back on dragleave
                sectionMap.on("dragleave", function () {
                    $(this).css('background-color', settings.defaultBackgroundColor);
                });

                // Change background of section on dragover
                sectionMap.on("dragover", function (event) {
                    event.preventDefault();
                    $(this).css('background-color', settings.onDragBackgroundColor);
                });

                // On object drop create one, and render it on page
                sectionMap.on("drop", function (event) {
                    event.preventDefault();
                    $(this).css('background-color', settings.defaultBackgroundColor);

                    let section = $(this).closest('.section-content'),
                        sectionId = section.data('section-id'),
                        objectType = event.originalEvent.dataTransfer.getData("text/plain"),
                        offsetX = Math.round(event.originalEvent.offsetX / baseW * 100),
                        offsetY = Math.round(event.originalEvent.offsetY / baseH * 100),
                        percentY = offsetX + settings[objectType + "Width"] < 100 ? offsetX : 100 - settings[objectType + "Width"],
                        percentX = offsetY + settings[objectType + "Height"] < 100 ? offsetY : 100 - settings[objectType + "Height"];

                    if (objectType == 'table') {
                        addTableModal.modal('show');

                        let objectNumberInput = addTableModal.find('.object-num-input');
                        let personsNumberInput = addTableModal.find('.persons-num-input');

                        objectNumberInput.val(1);
                        personsNumberInput.val(settings.defaultPersonsNumber);

                        addTableButton.off('click');
                        addTableButton.on('click', function () {
                            $tooltips.tooltip('enable');
                            let objectNumber = objectNumberInput.val().replace(/\+/g, "");
                            let personsNumber = +personsNumberInput.val();
                            let personsValidation = true;
                            let tableValidation = true;

                            if (!personsNumber || personsNumber == 0) {
                                personsNumberInput.tooltip('show');
                                personsValidation = false;
                            } else {
                                personsNumberInput.tooltip('hide');
                                personsValidation = true
                            }

                            if (!objectNumber || tables.indexOf(objectNumber) != -1) {
                                objectNumberInput.tooltip('show');
                                tableValidation = false;
                            } else {
                                objectNumberInput.tooltip('hide');
                                tableValidation = true;
                            }

                            if (personsValidation && tableValidation) {
                                let newObject = new Object(Date.now(), sectionId, objectType, percentX, percentY, objectNumber, personsNumber);
                                addTableModal.modal('hide');
                                addObject(newObject, sectionId, section);
                            }
                        });
                    } else {
                        let newObject = new Object(Date.now(), sectionId, objectType, percentX, percentY);
                        addObject(newObject, sectionId, section);
                    }
                });
            }

            // Protect unsaved data
            function setOnBeforeUnload() {
                saveTablePlanBlock.fadeIn('slow');
                window.onbeforeunload = function () {
                    return true
                };
            }

            // Allow redirect
            function unsetOnBeforeUnload() {
                window.onbeforeunload = null;
            }

            // Push new object to section array
            function addObject(newObject, sectionId, section) {
                if (!settings.tablePlanObjects[sectionId]) {
                    settings.tablePlanObjects[sectionId] = [];
                }
                settings.tablePlanObjects[sectionId].push(newObject);
                renderObject(newObject, section, true);
            }

            // Set handlers for object properties change
            function editObjectsProperties(object) {

                let objectId = object.data('object-id');
                let sectionId = object.closest('.section-content').data('section-id');
                let tableIndex = getIndex(settings.tablePlanObjects[sectionId], objectId);
                let objectProperties = settings.tablePlanObjects[sectionId][tableIndex];
                let objectName = $('.js-restaurant-plan__tools-button[data-object-type="' + objectProperties.type + '"]').attr('title');

                // Disable border radius input if type of ofject is 'plant'
                if (object.data('object-type') === 'plant') {
                    objectBorderRadiusInput.attr('disabled', true);
                } else {
                    objectBorderRadiusInput.attr('disabled', false);
                }

                // Disable height input if type of object is 'pillar'
                // if (object.data('object-type') == 'pillar' || object.data('object-type') == 'plant') {
                //     objectHeightInput.attr('disabled', true);
                // } else {
                //     objectHeightInput.attr('disabled', false);
                // }

                // Deselect all objects
                $('.tableM-object').removeClass('active');

                // Select this editable object
                object.addClass('active');
                objectPropertiesBlock.addClass('active');

                // Fill up values to inputs
                objectTypeTitle.text(objectName);
                objectHeightInput.val(objectProperties.height);
                objectWidthInput.val(objectProperties.width);
                objectLabelInput.val(objectProperties.label);
                objectBorderRadiusInput.val(objectProperties.border_radius);

                // Drop previous event handlers
                objectTypeTitle.off('input');
                objectHeightInput.off('input');
                objectWidthInput.off('input');
                objectLabelInput.off('input');
                objectBorderRadiusInput.off('input');

                $('input[type="number"]').on('keydown', function (e) {
                    if (!$.isNumeric(e.key) && e.key !== 'Backspace') {
                        e.preventDefault();
                    }
                });

                // Set event listeners on properties input
                objectLabelInput.on('input', function () {
                    let labelInput = $(this).val();
                    object.find('.tableM-label').text(labelInput);
                    objectProperties.label = labelInput;
                    setOnBeforeUnload();
                });
                objectWidthInput.on('focus', function (e) {
                    oldWidth = $(this).val();
                });
                objectWidthInput.on('input', function (e) {
                    let widthInput = $(this).val();
                    if (( object.width() + object.position().left < baseW - 10 && object.height() + object.position().top < baseH - 10 ) || oldWidth > widthInput) {
                        object.css('width', widthInput + '%');
                        objectProperties.width = widthInput;
                        if (objectProperties.type === 'plant' || objectProperties.type === 'pillar') {
                            // same sides in proportions
                            widthInput = Math.round(widthInput * sectionMapProportion);
                            object.css('height', widthInput + '%');
                            objectProperties.height = widthInput;
                            objectHeightInput.val(widthInput);
                        }
                    } else {
                        e.preventDefault();
                        $(this).val(oldWidth);
                    }
                    setOnBeforeUnload();
                });
                objectHeightInput.on('focus', function (e) {
                    oldHeight = $(this).val();
                });
                objectHeightInput.on('input', function (e) {
                    let heightInput = $(this).val();
                    if (( object.height() + object.position().top < baseH - 10 && object.width() + object.position().left < baseW - 10 ) || oldHeight > heightInput) {
                        object.css('height', heightInput + '%');
                        objectProperties.height = heightInput;
                        if (objectProperties.type === 'plant' || objectProperties.type === 'pillar') {
                            // same sides in proportions
                            heightInput = Math.round(heightInput / sectionMapProportion);
                            object.css('width', heightInput + '%');
                            objectProperties.width = heightInput;
                            objectWidthInput.val(heightInput);
                        }
                    } else {
                        $(this).val(oldHeight);
                        e.preventDefault();
                    }
                    setOnBeforeUnload();
                });

                objectBorderRadiusInput.on('input', function () {
                    let borderRadiusInput = $(this).val();
                    object.css("border-radius", borderRadiusInput + '%');
                    objectProperties.border_radius = borderRadiusInput;
                    setOnBeforeUnload();
                });
            }

            // Get index of object in section array
            function getIndex(array, id, ignoreIndex) {

                for (let i = 0; i < array.length; i++) {
                    if (array[i].id == id) {
                        if (ignoreIndex == i) {
                            continue;
                        }
                        return i;
                    }
                }
                return -1;
            }

            // Store objects in hidden input
            function saveObjectsToInput() {
                TablePlanObjectsHiddenInput.val(JSON.stringify(settings.tablePlanObjects));
            }
        }
    }(jQuery)
);