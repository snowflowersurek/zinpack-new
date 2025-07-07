<!DOCTYPE html>
<!--
Copyright 2012 Mozilla Foundation

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

    http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.

Adobe CMap resources are covered by their own copyright but the same license:

    Copyright 1990-2015 Adobe Systems Incorporated.

See https://github.com/adobe-type-tools/cmap-resources
-->
<html dir="ltr" mozdisallowselectionprint>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="google" content="notranslate">
    <title>PDF.js viewer</title>

<!-- This snippet is used in production (included from viewer.html) -->
<link rel="resource" type="application/l10n" href="locale/locale.properties">
<script src="pdf.js" type="module"></script>

    <link rel="stylesheet" href="viewer.css">

  <script src="viewer.js" type="module"></script>
  </head>

  <body tabindex="0">
    <span id="viewer-alert" class="visuallyHidden" role="alert"></span>
    <div id="outerContainer">

      <div id="sidebarContainer">
        <div id="toolbarSidebar" class="toolbarHorizontalGroup">
          <div id="toolbarSidebarLeft">
            <div id="sidebarViewButtons" class="toolbarHorizontalGroup toggled" role="radiogroup">
              <button id="viewThumbnail" class="toolbarButton toggled" type="button" tabindex="0" data-l10n-id="thumbs" role="radio" aria-checked="true" aria-controls="thumbnailView">
                 <span data-l10n-id="thumbs_label">Thumbnails</span>
              </button>
              <button id="viewOutline" class="toolbarButton" type="button" tabindex="0" data-l10n-id="outline" role="radio" aria-checked="false" aria-controls="outlineView">
                 <span data-l10n-id="outline_label">Document Outline</span>
              </button>
              <button id="viewAttachments" class="toolbarButton" type="button" tabindex="0" data-l10n-id="attachments" role="radio" aria-checked="false" aria-controls="attachmentsView">
                 <span data-l10n-id="attachments_label">Attachments</span>
              </button>
              <button id="viewLayers" class="toolbarButton" type="button" tabindex="0" data-l10n-id="layers" role="radio" aria-checked="false" aria-controls="layersView">
                 <span data-l10n-id="layers_label">Layers</span>
              </button>
            </div>
          </div>

          <div id="toolbarSidebarRight">
            <div id="outlineOptionsContainer" class="toolbarHorizontalGroup">
              <div class="verticalToolbarSeparator"></div>

              <button id="currentOutlineItem" class="toolbarButton" type="button" disabled="disabled" tabindex="0" data-l10n-id="current_outline_item">
                <span data-l10n-id="current_outline_item_label">Current Outline Item</span>
              </button>
            </div>
          </div>
        </div>
        <div id="sidebarContent">
          <div id="thumbnailView">
          </div>
          <div id="outlineView" class="hidden">
          </div>
          <div id="attachmentsView" class="hidden">
          </div>
          <div id="layersView" class="hidden">
          </div>
        </div>
        <div id="sidebarResizer"></div>
      </div>  <!-- sidebarContainer -->

      <div id="mainContainer">
        <div class="toolbar">
          <div id="toolbarContainer">
            <div id="toolbarViewer" class="toolbarHorizontalGroup">
              <div id="toolbarViewerLeft" class="toolbarHorizontalGroup">
                <button id="sidebarToggleButton" class="toolbarButton" type="button" tabindex="0" data-l10n-id="toggle_sidebar" aria-expanded="false" aria-haspopup="true" aria-controls="sidebarContainer">
                  <span data-l10n-id="toggle_sidebar_label">Toggle Sidebar</span>
                </button>
                <div class="toolbarButtonSpacer"></div>
                <div class="toolbarButtonWithContainer">
                  <button id="viewFindButton" class="toolbarButton" type="button" tabindex="0" data-l10n-id="findbar" aria-expanded="false" aria-controls="findbar">
                    <span data-l10n-id="findbar_label">Find</span>
                  </button>
                  <div class="hidden doorHanger toolbarHorizontalGroup" id="findbar">
                    <div id="findInputContainer" class="toolbarHorizontalGroup">
                      <span class="loadingInput end toolbarHorizontalGroup">
                        <input id="findInput" class="toolbarField" tabindex="0" data-l10n-id="find_input" aria-invalid="false">
                      </span>
                      <div class="toolbarHorizontalGroup">
                        <button id="findPreviousButton" class="toolbarButton" type="button" tabindex="0" data-l10n-id="find_previous">
                          <span data-l10n-id="find_previous_label">Previous</span>
                        </button>
                        <div class="splitToolbarButtonSeparator"></div>
                        <button id="findNextButton" class="toolbarButton" type="button" tabindex="0" data-l10n-id="find_next">
                          <span data-l10n-id="find_next_label">Next</span>
                        </button>
                      </div>
                    </div>

                    <div id="findbarOptionsOneContainer" class="toolbarHorizontalGroup">
                      <div class="toggleButton toolbarLabel">
                        <input type="checkbox" id="findHighlightAll" tabindex="0" />
                        <label for="findHighlightAll" data-l10n-id="find_highlight">Highlight all</label>
                      </div>
                      <div class="toggleButton toolbarLabel">
                        <input type="checkbox" id="findMatchCase" tabindex="0" />
                        <label for="findMatchCase" data-l10n-id="find_match_case_label">Match case</label>
                      </div>
                    </div>
                    <div id="findbarOptionsTwoContainer" class="toolbarHorizontalGroup">
                      <div class="toggleButton toolbarLabel">
                        <input type="checkbox" id="findMatchDiacritics" tabindex="0" />
                        <label for="findMatchDiacritics" data-l10n-id="find_match_diacritics_label">Match diacritics</label>
                      </div>
                      <div class="toggleButton toolbarLabel">
                        <input type="checkbox" id="findEntireWord" tabindex="0" />
                        <label for="findEntireWord" data-l10n-id="find_entire_word_label">Whole words</label>
                      </div>
                    </div>

                    <div id="findbarMessageContainer" class="toolbarHorizontalGroup" aria-live="polite">
                      <span id="findResultsCount" class="toolbarLabel"></span>
                      <span id="findMsg" class="toolbarLabel"></span>
                    </div>
                  </div>  <!-- findbar -->
                </div>
                <div class="toolbarHorizontalGroup hiddenSmallView">
                  <button class="toolbarButton" type="button" id="previous" tabindex="0" data-l10n-id="previous">
                    <span data-l10n-id="previous_label">Previous</span>
                  </button>
                  <div class="splitToolbarButtonSeparator"></div>
                  <button class="toolbarButton" type="button" id="next" tabindex="0" data-l10n-id="next">
                    <span data-l10n-id="next_label">Next</span>
                  </button>
                </div>
                <div class="toolbarHorizontalGroup">
                  <span class="loadingInput start toolbarHorizontalGroup">
                    <input type="number" id="pageNumber" class="toolbarField" value="1" min="1" tabindex="0" data-l10n-id="page_input" autocomplete="off">
                  </span>
                  <span id="numPages" class="toolbarLabel"></span>
                </div>
              </div>
              <div id="toolbarViewerMiddle" class="toolbarHorizontalGroup">
                <div class="toolbarHorizontalGroup">
                  <button id="zoomOutButton" class="toolbarButton" type="button" tabindex="0" data-l10n-id="zoom_out">
                    <span data-l10n-id="zoom_out_label">Zoom Out</span>
                  </button>
                  <div class="splitToolbarButtonSeparator"></div>
                  <button id="zoomInButton" class="toolbarButton" type="button" tabindex="0" data-l10n-id="zoom_in">
                    <span data-l10n-id="zoom_in_label">Zoom In</span>
                  </button>
                </div>
                <span id="scaleSelectContainer" class="dropdownToolbarButton">
                  <select id="scaleSelect" tabindex="0" data-l10n-id="zoom">
                    <option id="pageAutoOption" value="auto" selected="selected" data-l10n-id="page_scale_auto">Automatic Zoom</option>
                    <option id="pageActualOption" value="page-actual" data-l10n-id="page_scale_actual">Actual Size</option>
                    <option id="pageFitOption" value="page-fit" data-l10n-id="page_scale_fit">Fit Page</option>
                    <option id="pageWidthOption" value="page-width" data-l10n-id="page_scale_width">Full Width</option>
                    <option id="customScaleOption" value="custom" disabled="disabled" hidden="true" data-l10n-id="page_scale_percent" data-l10n-args='{ "scale": 0 }'>Custom (0%)</option>
                    <option value="0.5" data-l10n-id="page_scale_percent" data-l10n-args='{ "scale": 50 }'>50%</option>
                    <option value="0.75" data-l10n-id="page_scale_percent" data-l10n-args='{ "scale": 75 }'>75%</option>
                    <option value="1" data-l10n-id="page_scale_percent" data-l10n-args='{ "scale": 100 }'>100%</option>
                    <option value="1.25" data-l10n-id="page_scale_percent" data-l10n-args='{ "scale": 125 }'>125%</option>
                    <option value="1.5" data-l10n-id="page_scale_percent" data-l10n-args='{ "scale": 150 }'>150%</option>
                    <option value="2" data-l10n-id="page_scale_percent" data-l10n-args='{ "scale": 200 }'>200%</option>
                    <option value="3" data-l10n-id="page_scale_percent" data-l10n-args='{ "scale": 300 }'>300%</option>
                    <option value="4" data-l10n-id="page_scale_percent" data-l10n-args='{ "scale": 400 }'>400%</option>
                  </select>
                </span>
              </div>
              <div id="toolbarViewerRight" class="toolbarHorizontalGroup">
                <div class="toolbarHorizontalGroup hiddenMediumView">
                  <button id="printButton" class="toolbarButton" type="button" tabindex="0" data-l10n-id="print">
                    <span data-l10n-id="print_label">Print</span>
                  </button>

                  <button id="downloadButton" class="toolbarButton" type="button" tabindex="0" data-l10n-id="download">
                    <span data-l10n-id="download_label">Download</span>
                  </button>
                </div>

                <div class="verticalToolbarSeparator hiddenMediumView"></div>

                <div id="secondaryToolbarToggle" class="toolbarButtonWithContainer">
                  <button id="secondaryToolbarToggleButton" class="toolbarButton" type="button" tabindex="0" data-l10n-id="tools" aria-expanded="false" aria-haspopup="true" aria-controls="secondaryToolbar">
                    <span data-l10n-id="tools_label">Tools</span>
                  </button>
                  <div id="secondaryToolbar" class="hidden doorHangerRight menu">
                    <div id="secondaryToolbarButtonContainer" class="menuContainer">
                      <button id="secondaryOpenFile" class="toolbarButton labeled" type="button" tabindex="0" data-l10n-id="open_file">
                        <span data-l10n-id="open_file_label">Open</span>
                      </button>

                      <div class="visibleMediumView">
                        <button id="secondaryPrint" class="toolbarButton labeled" type="button" tabindex="0" data-l10n-id="print">
                          <span data-l10n-id="print_label">Print</span>
                        </button>

                        <button id="secondaryDownload" class="toolbarButton labeled" type="button" tabindex="0" data-l10n-id="download">
                          <span data-l10n-id="download_label">Download</span>
                        </button>

                      </div>

                      <div class="horizontalToolbarSeparator"></div>

                      <button id="presentationMode" class="toolbarButton labeled" type="button" tabindex="0" data-l10n-id="presentation_mode">
                        <span data-l10n-id="presentation_mode_label">Presentation Mode</span>
                      </button>

                      <a href="#" id="viewBookmark" class="toolbarButton labeled" tabindex="0" data-l10n-id="bookmark">
                        <span data-l10n-id="bookmark_label">Current View</span>
                      </a>

                      <div id="viewBookmarkSeparator" class="horizontalToolbarSeparator"></div>

                      <button id="firstPage" class="toolbarButton labeled" type="button" tabindex="0" data-l10n-id="first_page">
                        <span data-l10n-id="first_page_label">Go to First Page</span>
                      </button>
                      <button id="lastPage" class="toolbarButton labeled" type="button" tabindex="0" data-l10n-id="last_page">
                        <span data-l10n-id="last_page_label">Go to Last Page</span>
                      </button>

                      <div class="horizontalToolbarSeparator"></div>

                      <button id="pageRotateCw" class="toolbarButton labeled" type="button" tabindex="0" data-l10n-id="page_rotate_cw">
                        <span data-l10n-id="page_rotate_cw_label">Rotate Clockwise</span>
                      </button>
                      <button id="pageRotateCcw" class="toolbarButton labeled" type="button" tabindex="0" data-l10n-id="page_rotate_ccw">
                        <span data-l10n-id="page_rotate_ccw_label">Rotate Counterclockwise</span>
                      </button>

                      <div class="horizontalToolbarSeparator"></div>

                      <div id="cursorToolButtons" role="radiogroup">
                        <button id="cursorSelectTool" class="toolbarButton labeled toggled" type="button" tabindex="0" data-l10n-id="cursor_text_select_tool" role="radio" aria-checked="true">
                          <span data-l10n-id="cursor_text_select_tool_label">Text Selection Tool</span>
                        </button>
                        <button id="cursorHandTool" class="toolbarButton labeled" type="button" tabindex="0" data-l10n-id="cursor_hand_tool" role="radio" aria-checked="false">
                          <span data-l10n-id="cursor_hand_tool_label">Hand Tool</span>
                        </button>
                      </div>

                      <div class="horizontalToolbarSeparator"></div>

                      <div id="scrollModeButtons" role="radiogroup">
                        <button id="scrollPage" class="toolbarButton labeled" type="button" tabindex="0" data-l10n-id="scroll_page" role="radio" aria-checked="false">
                          <span data-l10n-id="scroll_page_label">Use Page Scrolling</span>
                        </button>
                        <button id="scrollVertical" class="toolbarButton labeled toggled" type="button" tabindex="0" data-l10n-id="scroll_vertical" role="radio" aria-checked="true">
                          <span data-l10n-id="scroll_vertical_label">Use Vertical Scrolling</span>
                        </button>
                        <button id="scrollHorizontal" class="toolbarButton labeled" type="button" tabindex="0" data-l10n-id="scroll_horizontal" role="radio" aria-checked="false">
                          <span data-l10n-id="scroll_horizontal_label">Use Horizontal Scrolling</span>
                        </button>
                        <button id="scrollWrapped" class="toolbarButton labeled" type="button" tabindex="0" data-l10n-id="scroll_wrapped" role="radio" aria-checked="false">
                          <span data-l10n-id="scroll_wrapped_label">Use Wrapped Scrolling</span>
                        </button>
                      </div>

                      <div class="horizontalToolbarSeparator"></div>

                      <div id="spreadModeButtons" role="radiogroup">
                        <button id="spreadNone" class="toolbarButton labeled toggled" type="button" tabindex="0" data-l10n-id="spread_none" role="radio" aria-checked="true">
                          <span data-l10n-id="spread_none_label">No Spreads</span>
                        </button>
                        <button id="spreadOdd" class="toolbarButton labeled" type="button" tabindex="0" data-l10n-id="spread_odd" role="radio" aria-checked="false">
                          <span data-l10n-id="spread_odd_label">Odd Spreads</span>
                        </button>
                        <button id="spreadEven" class="toolbarButton labeled" type="button" tabindex="0" data-l10n-id="spread_even" role="radio" aria-checked="false">
                          <span data-l10n-id="spread_even_label">Even Spreads</span>
                        </button>
                      </div>

                      <div class="horizontalToolbarSeparator"></div>

                      <button id="documentProperties" class="toolbarButton labeled" type="button" tabindex="0" data-l10n-id="document_properties" aria-controls="documentPropertiesDialog">
                        <span data-l10n-id="document_properties_label">Document Properties…</span>
                      </button>
                    </div>
                  </div>  <!-- secondaryToolbar -->
                </div>
              </div>
            </div>
            <div id="loadingBar">
              <div class="progress">
                <div class="glimmer">
                </div>
              </div>
            </div>
          </div>
        </div>

        <div id="viewerContainer" tabindex="0">
          <div id="viewer" class="pdfViewer"></div>
        </div>
      </div> <!-- mainContainer -->

      <div id="dialogContainer">
        <dialog id="passwordDialog">
          <div class="row">
            <label for="password" id="passwordText" data-l10n-id="password_label">Enter the password to open this PDF file:</label>
          </div>
          <div class="row">
            <input type="password" id="password" class="toolbarField">
          </div>
          <div class="buttonRow">
            <button id="passwordCancel" class="dialogButton" type="button"><span data-l10n-id="password_cancel">Cancel</span></button>
            <button id="passwordSubmit" class="dialogButton" type="button"><span data-l10n-id="password_ok">OK</span></button>
          </div>
        </dialog>
        <dialog id="documentPropertiesDialog">
          <div class="row">
            <span id="fileNameLabel" data-l10n-id="document_properties_file_name">File name:</span>
            <p id="fileNameField" aria-labelledby="fileNameLabel">-</p>
          </div>
          <div class="row">
            <span id="fileSizeLabel" data-l10n-id="document_properties_file_size">File size:</span>
            <p id="fileSizeField" aria-labelledby="fileSizeLabel">-</p>
          </div>
          <div class="separator"></div>
          <div class="row">
            <span id="titleLabel" data-l10n-id="document_properties_title">Title:</span>
            <p id="titleField" aria-labelledby="titleLabel">-</p>
          </div>
          <div class="row">
            <span id="authorLabel" data-l10n-id="document_properties_author">Author:</span>
            <p id="authorField" aria-labelledby="authorLabel">-</p>
          </div>
          <div class="row">
            <span id="subjectLabel" data-l10n-id="document_properties_subject">Subject:</span>
            <p id="subjectField" aria-labelledby="subjectLabel">-</p>
          </div>
          <div class="row">
            <span id="keywordsLabel" data-l10n-id="document_properties_keywords">Keywords:</span>
            <p id="keywordsField" aria-labelledby="keywordsLabel">-</p>
          </div>
          <div class="row">
            <span id="creationDateLabel" data-l10n-id="document_properties_creation_date">Creation Date:</span>
            <p id="creationDateField" aria-labelledby="creationDateLabel">-</p>
          </div>
          <div class="row">
            <span id="modificationDateLabel" data-l10n-id="document_properties_modification_date">Modification Date:</span>
            <p id="modificationDateField" aria-labelledby="modificationDateLabel">-</p>
          </div>
          <div class="row">
            <span id="creatorLabel" data-l10n-id="document_properties_creator">Creator:</span>
            <p id="creatorField" aria-labelledby="creatorLabel">-</p>
          </div>
          <div class="separator"></div>
          <div class="row">
            <span id="producerLabel" data-l10n-id="document_properties_producer">PDF Producer:</span>
            <p id="producerField" aria-labelledby="producerLabel">-</p>
          </div>
          <div class="row">
            <span id="versionLabel" data-l10n-id="document_properties_version">PDF Version:</span>
            <p id="versionField" aria-labelledby="versionLabel">-</p>
          </div>
          <div class="row">
            <span id="pageCountLabel" data-l10n-id="document_properties_page_count">Page Count:</span>
            <p id="pageCountField" aria-labelledby="pageCountLabel">-</p>
          </div>
          <div class="row">
            <span id="pageSizeLabel" data-l10n-id="document_properties_page_size">Page Size:</span>
            <p id="pageSizeField" aria-labelledby="pageSizeLabel">-</p>
          </div>
          <div class="separator"></div>
          <div class="row">
            <span id="linearizedLabel" data-l10n-id="document_properties_linearized">Fast Web View:</span>
            <p id="linearizedField" aria-labelledby="linearizedLabel">-</p>
          </div>
          <div class="buttonRow">
            <button id="documentPropertiesClose" class="dialogButton" type="button"><span data-l10n-id="document_properties_close">Close</span></button>
          </div>
        </dialog>
      </div>  <!-- dialogContainer -->

    </div> <!-- outerContainer -->
    <div id="printContainer"></div>

  </body>
</html>





