<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<script>
    var weekly_payments_statistics;
    var user_dashboard_visibility = [];
    $(function() {
        $("[data-container]").sortable({
            connectWith: "[data-container]",
            helper: 'clone',
            handle: '.widget-dragger',
            tolerance: 'pointer',
            forcePlaceholderSize: true,
            placeholder: 'placeholder-dashboard-widgets',
            start: function (event, ui) {
                $("body,#wrapper").addClass('noscroll');
                $('body').find('[data-container]').css('min-height', '20px');
            },
            stop: function (event, ui) {
                $("body,#wrapper").removeClass('noscroll');
                $('body').find('[data-container]').removeAttr('style');
            },
            update: function (event, ui) {
                if (this === ui.item.parent()[0]) {
                    var data = {};
                    $.each($("[data-container]"), function () {
                        var cId = $(this).attr('data-container');
                        data[cId] = $(this).sortable('toArray');
                        if (data[cId].length == 0) {
                            data[cId] = 'empty';
                        }
                    });
                    if (typeof (csrfData) !== 'undefined') {
                        data[csrfData['token_name']] = csrfData['hash'];
                    }
                    $.post(admin_url + 'staff/save_dashboard_widgets_order_client', data, "json");
                }
            }
        });

        // Read more for dashboard todo items
        $('.read-more').readmore({
            collapsedHeight: 150,
            moreLink: "<a href=\"#\"><?php echo _l('read_more'); ?></a>",
            lessLink: "<a href=\"#\"><?php echo _l('show_less'); ?></a>",
        });

        $('body').on('click', '#viewWidgetableArea', function (e) {
            e.preventDefault();

            if (!$(this).hasClass('preview')) {
                $(this).html("<?php echo _l('hide_widgetable_area'); ?>");
                $('[data-container]').append('<div class="placeholder-dashboard-widgets pl-preview"></div>');
            } else {
                $(this).html("<?php echo _l('view_widgetable_area'); ?>");
                $('[data-container]').find('.pl-preview').remove();
            }

            $('[data-container]').toggleClass('preview-widgets');
            $(this).toggleClass('preview');
        });

        var $widgets = $('.widget');
        var widgetsOptionsHTML = '';
        widgetsOptionsHTML += '<div id="dashboard-options">';
        widgetsOptionsHTML += "<h4><i class='fa fa-question-circle' data-toggle='tooltip' data-placement=\"bottom\" data-title=\"<?php echo _l('widgets_visibility_help_text'); ?>\"></i> <?php echo _l('widgets'); ?></h4><a href=\"<?php echo admin_url('staff/reset_dashboard'); ?>\"><?php echo _l('reset_dashboard'); ?></a>";

        widgetsOptionsHTML += ' | <a href=\"#\" id="viewWidgetableArea"><?php echo _l('view_widgetable_area'); ?></a>';
        widgetsOptionsHTML += '<hr class=\"hr-10\">';

        $.each($widgets, function () {
            var widget = $(this);
            var widgetOptionsHTML = '';
            if (widget.data('name') && widget.html().trim().length > 0) {
                widgetOptionsHTML += '<div class="checkbox checkbox-inline">';
                var wID = widget.attr('id');
                wID = wID.split('widget-');
                wID = wID[wID.length - 1];
                var checked = ' ';
                var db_result = $.grep(user_dashboard_visibility, function (e) {
                    return e.id == wID;
                });
                if (db_result.length >= 0) {
                    // no options saved or really visible
                    if (typeof (db_result[0]) == 'undefined' || db_result[0]['visible'] == 1) {
                        checked = ' checked ';
                    }
                }
                widgetOptionsHTML += '<input type="checkbox" class="widget-visibility" value="' + wID + '"' + checked + 'id="widget_option_' + wID + '" name="dashboard_widgets[' + wID + ']">';
                widgetOptionsHTML += '<label for="widget_option_' + wID + '">' + widget.data('name') + '</label>';
                widgetOptionsHTML += '</div>';
            }
            widgetsOptionsHTML += widgetOptionsHTML;
        });

        $('.screen-options-area').append(widgetsOptionsHTML);
        $('body').find('#dashboard-options input.widget-visibility').on('change', function () {
            if ($(this).prop('checked') == false) {
                $('#widget-' + $(this).val()).addClass('hide');
            } else {
                $('#widget-' + $(this).val()).removeClass('hide');
            }

            var data = {};
            var options = $('#dashboard-options input[type="checkbox"]').map(function () {
                return {id: this.value, visible: this.checked ? 1 : 0};
            }).get();

            data.widgets = options;
            /*
                    if (typeof(csrfData) !== 'undefined') {
                        data[csrfData['token_name']] = csrfData['hash'];
                    }
            */
            $.post(admin_url + 'staff/save_dashboard_widgets_visibility', data).fail(function (data) {
                // Demo usage, prevent multiple alerts
                if ($('body').find('.float-alert').length == 0) {
                    alert_float('danger', data.responseText);
                }
            });
        });

        var leads_time_stats = $('#chart-leads_leads_time_stats');

        if (leads_time_stats.length > 0) {
            // Leads overview status
            new Chart(leads_time_stats, {
                type: 'doughnut',
                data: <?php echo !empty($leads_time_stats) ? $leads_time_stats : '[]'; ?>,
                options: {
                    maintainAspectRatio: false,
                    onClick: function (evt) {
                        onChartClickRedirect(evt, this);
                    }
                }
            });
        }

        var chart_client_time_stats = $('#chart-client_time_stats');
        if (chart_client_time_stats.length > 0) {
            new Chart(chart_client_time_stats, {
                type: 'doughnut',
                data: <?php echo !empty($client_time_stats) ? $client_time_stats : '[]'; ?>,
                options: {
                    maintainAspectRatio: false,
                    onClick: function (evt) {
                        onChartClickRedirect(evt, this);
                    }
                }
            });
        }

        if ($(window).width() < 500) {
            // Fix for small devices weekly payment statistics
            $('#weekly-payment-statistics').attr('height', '250');
        }

        fix_user_data_widget_tabs();
        $(window).on('resize', function () {
            $('.horizontal-scrollable-tabs ul.nav-tabs-horizontal').removeAttr('style');
            fix_user_data_widget_tabs();
        });
    });
    function fix_user_data_widget_tabs(){
        if ((app.browser != 'firefox' && isRTL == 'false' && is_mobile()) || (app.browser == 'firefox' && isRTL == 'false' && is_mobile())) {
            $('.horizontal-scrollable-tabs ul.nav-tabs-horizontal').css('margin-bottom', '26px');
        }
    }
</script>
<script id="code">
    function init() {
      var $ = go.GraphObject.make;  // for conciseness in defining templates
      myDiagram =
        $(go.Diagram, "myDiagramDiv",  // must name or refer to the DIV HTML element
          {
            grid: $(go.Panel, "Grid",
              $(go.Shape, "LineH", { stroke: "lightgray", strokeWidth: 0.5 }),
              $(go.Shape, "LineH", { stroke: "gray", strokeWidth: 0.5, interval: 10 }),
              $(go.Shape, "LineV", { stroke: "lightgray", strokeWidth: 0.5 }),
              $(go.Shape, "LineV", { stroke: "gray", strokeWidth: 0.5, interval: 10 })
            ),
            "draggingTool.dragsLink": true,
            "draggingTool.isGridSnapEnabled": true,
            "linkingTool.isUnconnectedLinkValid": true,
            "linkingTool.portGravity": 20,
            "relinkingTool.isUnconnectedLinkValid": true,
            "relinkingTool.portGravity": 20,
            "relinkingTool.fromHandleArchetype":
              $(go.Shape, "Diamond", { segmentIndex: 0, cursor: "pointer", desiredSize: new go.Size(8, 8), fill: "tomato", stroke: "darkred" }),
            "relinkingTool.toHandleArchetype":
              $(go.Shape, "Diamond", { segmentIndex: -1, cursor: "pointer", desiredSize: new go.Size(8, 8), fill: "darkred", stroke: "tomato" }),
            "linkReshapingTool.handleArchetype":
              $(go.Shape, "Diamond", { desiredSize: new go.Size(7, 7), fill: "lightblue", stroke: "deepskyblue" }),
            "rotatingTool.handleAngle": 270,
            "rotatingTool.handleDistance": 30,
            "rotatingTool.snapAngleMultiple": 15,
            "rotatingTool.snapAngleEpsilon": 15,
            "undoManager.isEnabled": true
          });
      // when the document is modified, add a "*" to the title and enable the "Save" button
      myDiagram.addDiagramListener("Modified", function(e) {
        var button = document.getElementById("SaveButton");
        // if (button) button.disabled = !myDiagram.isModified;
        var idx = document.title.indexOf("*");
        if (myDiagram.isModified) {
          if (idx < 0) document.title += "*";
        } else {
          if (idx >= 0) document.title = document.title.substr(0, idx);
        }
      });
      // Define a function for creating a "port" that is normally transparent.
      // The "name" is used as the GraphObject.portId, the "spot" is used to control how links connect
      // and where the port is positioned on the node, and the boolean "output" and "input" arguments
      // control whether the user can draw links from or to the port.
      function makePort(name, spot, output, input) {
        // the port is basically just a small transparent square
        return $(go.Shape, "Circle",
          {
            fill: null,  // not seen, by default; set to a translucent gray by showSmallPorts, defined below
            stroke: null,
            desiredSize: new go.Size(7, 7),
            alignment: spot,  // align the port on the main Shape
            alignmentFocus: spot,  // just inside the Shape
            portId: name,  // declare this object to be a "port"
            fromSpot: spot, toSpot: spot,  // declare where links may connect at this port
            fromLinkable: output, toLinkable: input,  // declare whether the user may draw links to/from here
            cursor: "pointer"  // show a different cursor to indicate potential link point
          });
      }
      var nodeSelectionAdornmentTemplate =
        $(go.Adornment, "Auto",
          $(go.Shape, { fill: null, stroke: "deepskyblue", strokeWidth: 1.5, strokeDashArray: [4, 2] }),
          $(go.Placeholder)
        );
      var nodeResizeAdornmentTemplate =
        $(go.Adornment, "Spot",
          { locationSpot: go.Spot.Right },
          $(go.Placeholder),
          $(go.Shape, { alignment: go.Spot.TopLeft, cursor: "nw-resize", desiredSize: new go.Size(6, 6), fill: "lightblue", stroke: "deepskyblue" }),
          $(go.Shape, { alignment: go.Spot.Top, cursor: "n-resize", desiredSize: new go.Size(6, 6), fill: "lightblue", stroke: "deepskyblue" }),
          $(go.Shape, { alignment: go.Spot.TopRight, cursor: "ne-resize", desiredSize: new go.Size(6, 6), fill: "lightblue", stroke: "deepskyblue" }),
          $(go.Shape, { alignment: go.Spot.Left, cursor: "w-resize", desiredSize: new go.Size(6, 6), fill: "lightblue", stroke: "deepskyblue" }),
          $(go.Shape, { alignment: go.Spot.Right, cursor: "e-resize", desiredSize: new go.Size(6, 6), fill: "lightblue", stroke: "deepskyblue" }),
          $(go.Shape, { alignment: go.Spot.BottomLeft, cursor: "se-resize", desiredSize: new go.Size(6, 6), fill: "lightblue", stroke: "deepskyblue" }),
          $(go.Shape, { alignment: go.Spot.Bottom, cursor: "s-resize", desiredSize: new go.Size(6, 6), fill: "lightblue", stroke: "deepskyblue" }),
          $(go.Shape, { alignment: go.Spot.BottomRight, cursor: "sw-resize", desiredSize: new go.Size(6, 6), fill: "lightblue", stroke: "deepskyblue" })
        );
      var nodeRotateAdornmentTemplate =
        $(go.Adornment,
          { locationSpot: go.Spot.Center, locationObjectName: "CIRCLE" },
          $(go.Shape, "Circle", { name: "CIRCLE", cursor: "pointer", desiredSize: new go.Size(7, 7), fill: "lightblue", stroke: "deepskyblue" }),
          $(go.Shape, { geometryString: "M3.5 7 L3.5 30", isGeometryPositioned: true, stroke: "deepskyblue", strokeWidth: 1.5, strokeDashArray: [4, 2] })
        );
      myDiagram.nodeTemplate =
        $(go.Node, "Spot",
          { locationSpot: go.Spot.Center },
          new go.Binding("location", "loc", go.Point.parse).makeTwoWay(go.Point.stringify),
          { selectable: true, selectionAdornmentTemplate: nodeSelectionAdornmentTemplate },
          { resizable: true, resizeObjectName: "PANEL", resizeAdornmentTemplate: nodeResizeAdornmentTemplate },
          { rotatable: true, rotateAdornmentTemplate: nodeRotateAdornmentTemplate },
          new go.Binding("angle").makeTwoWay(),
          // the main object is a Panel that surrounds a TextBlock with a Shape
          $(go.Panel, "Auto",
            { name: "PANEL" },
            new go.Binding("desiredSize", "size", go.Size.parse).makeTwoWay(go.Size.stringify),
            $(go.Shape, "Rectangle",  // default figure
              {
                portId: "", // the default port: if no spot on link data, use closest side
                fromLinkable: true, toLinkable: true, cursor: "pointer",
                fill: "white",  // default color
                strokeWidth: 2
              },
              new go.Binding("figure"),
              new go.Binding("fill")),
            $(go.TextBlock,
              {
                font: "bold 11pt Helvetica, Arial, sans-serif",
                margin: 8,
                maxSize: new go.Size(160, NaN),
                wrap: go.TextBlock.WrapFit,
                editable: true
              },
              new go.Binding("text").makeTwoWay())
          ),
          // four small named ports, one on each side:
          makePort("T", go.Spot.Top, false, true),
          makePort("L", go.Spot.Left, true, true),
          makePort("R", go.Spot.Right, true, true),
          makePort("B", go.Spot.Bottom, true, false),
          { // handle mouse enter/leave events to show/hide the ports
            mouseEnter: function(e, node) { showSmallPorts(node, true); },
            mouseLeave: function(e, node) { showSmallPorts(node, false); }
          }
        );
      function showSmallPorts(node, show) {
        node.ports.each(function(port) {
          if (port.portId !== "") {  // don't change the default port, which is the big shape
            port.fill = show ? "rgba(0,0,0,.3)" : null;
          }
        });
      }
      var linkSelectionAdornmentTemplate =
        $(go.Adornment, "Link",
          $(go.Shape,
            // isPanelMain declares that this Shape shares the Link.geometry
            { isPanelMain: true, fill: null, stroke: "deepskyblue", strokeWidth: 0 })  // use selection object's strokeWidth
        );
      myDiagram.linkTemplate =
        $(go.Link,  // the whole link panel
          { selectable: true, selectionAdornmentTemplate: linkSelectionAdornmentTemplate },
          { relinkableFrom: true, relinkableTo: true, reshapable: true },
          {
            routing: go.Link.AvoidsNodes,
            curve: go.Link.JumpOver,
            corner: 5,
            toShortLength: 4
          },
          new go.Binding("points").makeTwoWay(),
          $(go.Shape,  // the link path shape
            { isPanelMain: true, strokeWidth: 2 }),
          $(go.Shape,  // the arrowhead
            { toArrow: "Standard", stroke: null }),
          $(go.Panel, "Auto",
            new go.Binding("visible", "isSelected").ofObject(),
            $(go.Shape, "RoundedRectangle",  // the link shape
              { fill: "#F8F8F8", stroke: null }),
            $(go.TextBlock,
              {
                textAlign: "center",
                font: "10pt helvetica, arial, sans-serif",
                stroke: "#919191",
                margin: 2,
                minSize: new go.Size(10, NaN),
                editable: true
              },
              new go.Binding("text").makeTwoWay())
          )
        );
      load();  // load an initial diagram from some JSON text
      // initialize the Palette that is on the left side of the page
      myPalette =
        $(go.Palette, "myPaletteDiv",  // must name or refer to the DIV HTML element
          {
            maxSelectionCount: 1,
            nodeTemplateMap: myDiagram.nodeTemplateMap,  // share the templates used by myDiagram
            linkTemplate: // simplify the link template, just in this Palette
              $(go.Link,
                { // because the GridLayout.alignment is Location and the nodes have locationSpot == Spot.Center,
                  // to line up the Link in the same manner we have to pretend the Link has the same location spot
                  locationSpot: go.Spot.Center,
                  selectionAdornmentTemplate:
                    $(go.Adornment, "Link",
                      { locationSpot: go.Spot.Center },
                      $(go.Shape,
                        { isPanelMain: true, fill: null, stroke: "deepskyblue", strokeWidth: 0 }),
                      $(go.Shape,  // the arrowhead
                        { toArrow: "Standard", stroke: null })
                    )
                },
                {
                  routing: go.Link.AvoidsNodes,
                  curve: go.Link.JumpOver,
                  corner: 5,
                  toShortLength: 4
                },
                new go.Binding("points"),
                $(go.Shape,  // the link path shape
                  { isPanelMain: true, strokeWidth: 2 }),
                $(go.Shape,  // the arrowhead
                  { toArrow: "Standard", stroke: null })
              ),
            model: new go.GraphLinksModel(
            [  // specify the contents of the Palette
              { text: "Start", figure: "Circle", fill: "#ffffff" },
              { text: "Start", figure: "Circle", fill: "#00AD5F" },
              { text: "Step" },
              { text: "DB", figure: "Database", fill: "lightgray" },
              { text: "???", figure: "Diamond", fill: "lightskyblue" },
              { text: "End", figure: "Circle", fill: "#CE0620" },
              { text: "Comment", figure: "RoundedRectangle", fill: "lightyellow" }
            ], [
                // the Palette also has a disconnected Link, which the user can drag-and-drop
                { points: new go.List(/*go.Point*/).addAll([new go.Point(0, 0), new go.Point(30, 0), new go.Point(30, 40), new go.Point(60, 40)]) }
            ])
          });
    }
    // Show the diagram's model in JSON format that the user may edit
    function save()
    {
        saveDiagramProperties();  // do this first, before writing to JSON
        document.getElementById("FlowChart_Dashboard").value = myDiagram.model.toJson();
        myDiagram.isModified = false;

        $.post(admin_url+'clients/SaveflowChart', {toJson : myDiagram.model.toJson(),[csrfData['token_name']] : csrfData['hash']}, function(data){
            data = JSON.parse(data);
            alert_float(data.alert_type, data.message);
            $('#SaveButton').button('reset');
        })
    }

    function load()
    {
        myDiagram.model = go.Model.fromJson(document.getElementById("FlowChart_Dashboard").value);
        loadDiagramProperties();
    }
    function ClearFlowChart()
    {
        myDiagram.model = go.Model.fromJson('{ "class": "GraphLinksModel",'+
            '            "linkFromPortIdProperty": "fromPort",'+
            '            "linkToPortIdProperty": "toPort",'+
            '            "modelData": {"position":"-1025.8428640143816 -293"},'+
            '            "nodeDataArray": [],'+
            '            "linkDataArray": []'+
            '        }');
        loadDiagramProperties();

    }
    function saveDiagramProperties() {
      myDiagram.model.modelData.position = go.Point.stringify(myDiagram.position);
    }
    function loadDiagramProperties(e) {
      // set Diagram.initialPosition, not Diagram.position, to handle initialization side-effects
      var pos = myDiagram.model.modelData.position;
      if (pos) myDiagram.initialPosition = go.Point.parse(pos);
    }
</script>