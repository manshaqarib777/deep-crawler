<div class="container">
    <div id="wrapper">
        <div id="transform-form" class="modal " tabindex="-1">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <ul class="nav nav-tabs modal-tabs">
                            <li v-on:click="transformTab = 'find-replace'" :class="{'active' : transformTab == 'find-replace'}"><a href="javascript:;">Find & Replace</a></li>
                            <li v-show="selectedField.type != 'featured_image' && selectedField.type != 'image' && selectedField.type != 'gallery'" v-on:click="transformTab = 'math-functions'" :class="{'active' : transformTab == 'math-functions'}"><a href="javascript:;">Math Functions</a></li>
                            <li v-on:click="transformTab = 'strip-clean'" :class="{'active' : transformTab == 'strip-clean'}"><a href="javascript:;">Clean & Split</a></li>
                            <li v-on:click="transformTab = 'content-clip'" :class="{'active' : transformTab == 'content-clip'}"><a href="javascript:;">Clip</a></li>
                            <li v-show="selectedField.type != 'featured_image' && selectedField.type != 'image' && selectedField.type != 'gallery'" v-on:click="transformTab = 'translation'" :class="{'active' : transformTab == 'translation'}"><a href="javascript:;">Translation</a></li>
                            <li v-on:click="transformTab = 'database'" :class="{'active' : transformTab == 'database'}"><a href="javascript:;">Database</a></li>
                            <li v-show="selectedField.type != 'featured_image' && selectedField.type != 'image' && selectedField.type != 'gallery'" v-on:click="transformTab = 'spin-content'" :class="{'active' : transformTab == 'spin-content'}"><a href="javascript:;">Spin</a></li>
                            <li v-on:click="transformTab = 'shortcodes'" :class="{'active' : transformTab == 'shortcodes'}"><a href="javascript:;">Shortcodes</a></li>
                            <li v-show="selectedField.type == 'featured_image' || selectedField.type == 'image' || selectedField.type == 'gallery'" v-on:click="transformTab = 'image-name'" :class="{'active' : transformTab == 'image-name'}"><a href="javascript:;">Image Name</a></li>
                        </ul>
                    </div>
                    <div class="modal-body" v-if="selectedField">
                        <div class="row">
                            <div v-show="transformTab == 'find-replace'" class="col-md-6">
                                <h4>Find and replace</h4>
                                <p>It finds and replaces some strings in content. It can be used for all content types like image URLs, attributes and text contents.</p>

                                <p>If you want to replace special characters like . (dot) or / (slash), you should type them with backslash. For example : <code>\.</code></p>

                                <div class="scroll-view">
                                    <div class="scroll-item-group" v-for="(find_replace, $index) in selectedField.replaces">
                                        <h6>
                                            Replace Group #{{$index}}
                                            <a class="pull-right" href="javascript:;" v-on:click="deleteFindReplaceRule($index)">&times;</a>
                                        </h6>
                                        <div class="input-group">
                                            <div class="input-group-addon">Find</div>
                                            <input type="text" v-model="find_replace.find" class="form-control">
                                        </div>

                                        <div class="input-group">
                                            <div class="input-group-addon">Replace</div>
                                            <input type="text" v-model="find_replace.replace" class="form-control">
                                        </div>
                                    </div>
                                </div>

                                <button v-on:click="addFindReplaceRule()" class="btn btn-default btn-block"><i class="fas fa-plus-circle"></i> Add Another Rule</button>
                            </div>

                            <div v-show="transformTab == 'math-functions'" class="col-md-6">
                                <h4>Math Functions</h4>

                                <p>Perform mathematical operations with content. You could use these operations with "value" variable.</p>
                                <ul>
                                    <li><code>+</code> (addition)</li>
                                    <li><code>-</code> (subtraction)</li>
                                    <li><code>/</code> (division)</li>
                                    <li><code>*</code> (multiplication)</li>
                                </ul>

                                <p>
                                    <label>
                                        <input type="checkbox" v-model="selectedField.isNumber" value="true"> The content is numerical, enable math functions.
                                    </label>
                                </p>

                                <p v-show="selectedField.isNumber">
                                    <label>
                                        <input type="checkbox" v-model="selectedField.cleanNonNumerical" value="true"> Clean all non-numerical characters from content.
                                    </label>
                                </p>

                                <div v-show="selectedField.isNumber" class="input-group">
                                    <div class="input-group-addon">Expression</div>
                                    <input type="text" :disabled="!selectedField.isNumber" v-model="selectedField.math" placeholder="value + 5" class="form-control">
                                    <div class="input-group-addon" v-show="selectedField.isNumber">{{getSampleContent(selectedField.element, selectedField.prop, selectedField)}}</div>
                                </div>
                            </div>

                            <div v-show="transformTab == 'strip-clean'" class="col-md-6">
                                <h4>Clean HTML tags</h4>

                                <p>This function removes all HTML tags and strips all links from source content.</p>

                                <p>
                                    <label><input v-model="selectedField.stripLinks" type="checkbox" value="true"> Strip all links from source content</label>
                                </p>

                                <p>
                                    <label><input v-model="selectedField.stripTags" type="checkbox" value="true"> Strip HTML tags from source content</label>
                                </p>

                                <p>
                                    <label><input v-model="selectedField.stripAds" type="checkbox" value="true"> Clean Advertising tags from source content</label>
                                </p>

                                <p>
                                    <label><input v-model="selectedField.decodeBitly" type="checkbox" value="true"> Decode Bitly URLs</label>
                                </p>

                                <hr>
                                <h4>Split Content</h4>

                                <p>You could split content to array with delimiter.</p>
                                <p>
                                    <label><input v-model="selectedField.splitContent" type="checkbox" value="true"> Enable splitting</label>
                                </p>

                                <div class="input-group">
                                    <div class="input-group-addon">Delimiter</div>
                                    <input type="text" v-model="selectedField.splitDelimiter" class="form-control">
                                </div>
                            </div>

                            <div v-show="transformTab == 'content-clip'" class="col-md-6">
                                <h4>Clip Content by Characters</h4>

                                <p>This function clips source content with defined character count.</p>

                                <div class="input-group">
                                    <div class="input-group-addon">Start, End Characters</div>
                                    <input type="text" v-model="selectedField.clipStart" placeholder="0" class="form-control">
                                    <input type="text" v-model="selectedField.clipEnd" placeholder="0" class="form-control">
                                    <div class="input-group-addon">{{selectedField.clipEnd - selectedField.clipStart}}</div>
                                </div>

                                <hr>

                                <h4>Clip Content by Words</h4>

                                <p>This function clips source content with defined character count.</p>

                                <div class="input-group">
                                    <div class="input-group-addon">Start, End Words</div>
                                    <input type="text" v-model="selectedField.clipWordStart" placeholder="0" class="form-control">
                                    <input type="text" v-model="selectedField.clipWordEnd" placeholder="0" class="form-control">
                                </div>
                            </div>

                            <div v-show="transformTab == 'translation'" class="col-md-6">
                                <h4>Translation</h4>

                                <p>This function translates content to any language that supported by Google Translation API. Source content will automatically detected.</p>

                                <div class="input-group">
                                    <div class="input-group-addon">Language</div>
                                    <select class="form-control" v-model="selectedField.translate">
                                        <option value="">Don't Translate</option>
                                        <option :value="language.value" v-for="language in languages">{{language.label}}</option>
                                    </select>
                                </div>

                                <div class="alert alert-info"><i class="fas fa-exclamation-triangle"></i> Preview doesn't affect on this field.</div>
                            </div>

                            <div v-show="transformTab == 'database'" class="col-md-6">
                                <h4>Database Formats</h4>

                                <p><label><input type="checkbox" v-model="selectedField.isJSON" value="true"> Is multiple attribute stored in JSON format?</label></p>

                                <hr>

                                <h4>Database Name</h4>

                                <div class="input-group">
                                    <div class="input-group-addon">Field Name</div>
                                    <input type="text" v-model="selectedField.type" class="form-control">
                                </div>
                            </div>

                            <div v-show="transformTab == 'spin-content'" class="col-md-6">
                                <h4>Spin Content</h4>                               
                            </div>

                            <div v-show="transformTab == 'shortcodes'" class="col-md-6">
                                <h4>Shortcodes</h4>
                                <textarea v-model="selectedField.content" class="form-control"></textarea>

                                <p class="tags-container">Available Tags : <br>
                                    <small>
                                        <b v-for="variable in getVariableTags()" v-html="'{{' + variable.name + '}}, '"></b>
                                    </small>
                                </p>
                            </div>

                            <div v-show="transformTab == 'image-name'" class="col-md-6">
                                <h4>Image Name</h4>

                                <p>You could transform image name with shortcodes. It's possible to give unique name for your files. Please write name without extension.</p>

                                <div class="input-group">
                                    <div class="input-group-addon">Image Name</div>
                                    <input type="text" v-model="selectedField.filename" class="form-control">
                                </div>

                                <p class="tags-container">Available Tags : <br>
                                    <small>
                                        <b v-html="'{{index}}'"></b>
                                        <b v-html="'{{hash}}'"></b>
                                        <b v-html="'{{random}}'"></b>
                                    </small>
                                </p>

                                <hr>

                                <div class="input-group">
                                    <div class="input-group-addon">Name Preview</div>
                                    <input type="text" readonly v-model="processFilename(selectedField)" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div v-show="selectedField.type == 'featured_image' || selectedField.type == 'image' || selectedField.type == 'gallery'">
                                    <h4>Image Preview</h4>
                                    <a target="_blank" :href="getSampleContent(selectedField.element, selectedField.prop, selectedField)"><img class="image-preview" :src="getSampleContent(selectedField.element, selectedField.prop, selectedField)"></a>
                                    <h4>URL</h4>
                                    <textarea readonly class="form-control">{{getSampleContent(selectedField.element, selectedField.prop, selectedField)}}</textarea>
                                </div>

                                <div v-show="selectedField.type != 'featured_image' && selectedField.type != 'image' && selectedField.type != 'gallery'" class="image-preview">
                                    <h4>Source</h4>
                                    <textarea readonly class="form-control" >{{getSampleContent(selectedField.element, selectedField.prop, selectedField, true)}}</textarea>

                                    <hr>
                                    <h4>Transform Preview</h4>
                                    <textarea readonly class="form-control">{{getSampleContent(selectedField.element, selectedField.prop, selectedField)}}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button v-on:click="saveTransform()" type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>

        <div id="library" class="modal" tabindex="-1">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fas fa-search"></i>
                                    </span>
                                    <input type="search" class="form-control" v-model="form.search" placeholder="Search in scraping library">
                                </div>
                            </div>
                        </div>
                    </h4>
                    </div>
                    <div class="modal-body">
                        <div class="scrollable-content">
                            <ul class="list-group">
                                <li v-for="site in filterLibrary" class="list-group-item">
                                    {{site.name}}
                                    <span class="label label-danger">Community</span>
                                    <button :disabled="loading" v-on:click="applyTemplate(site.template)" class="btn btn-sm btn-primary pull-right"><i class="fas fa-download"></i> Use Template</button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="bulk-url-list" class="modal" tabindex="-1">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <ul class="nav nav-tabs modal-tabs">
                            <li v-on:click="bulkTab = 'url-list'" :class="{'active' : bulkTab == 'url-list'}"><a href="javascript:;">Bulk URL List</a></li>
                            <li v-on:click="bulkTab = 'pagination-generator'" :class="{'active' : bulkTab == 'pagination-generator'}"><a href="javascript:;">Pagination Generator</a></li>
                        </ul>
                    </div>
                    <div class="modal-body">
                        <span v-show="bulkTab == 'url-list'">
                            <p>The URL list will be scraped by plugin. Please enter URLs line by line.</p>
                            <textarea v-model="form.other.bulkURL" class="form-control"></textarea>
                        </span>

                        <span v-show="bulkTab == 'pagination-generator'">
                            <h4>Pagination Generator</h4>

                            <p>With pagination generator, you could generate bulk URL list with defined range. You should enter <code v-html="'{{number}}'"></code> as a dynamic variable on your URL, and generator will process and replace number variable with page numbers.</p>

                            <p>For example we have an URL like this : https://google.com/page=1</p>
                            <p>If you want to generate URLs with with this scheme, you could simply type <code>https://google.com/page=<b v-html="'{{number}}'"></b></code> in the input.</p>
                            <p>Generator will generate bulk URL list with defined URL scheme.</p>

                            <div class="input-group">
                                <div class="input-group-addon">Enter sample URL</div>
                                <input type="text" v-model="temp.paginationURL" class="form-control">
                            </div>

                            <div class="input-group">
                                <div class="input-group-addon">Start Page Number</div>
                                <input type="number" v-model="temp.startNumber" class="form-control">
                            </div>

                            <div class="input-group">
                                <div class="input-group-addon">End Page Number</div>
                                <input type="number" v-model="temp.endNumber" class="form-control">
                            </div>

                            <button v-on:click="generatePaginationList();" type="button" class="btn btn-info">Generate URL List</button>
                        </span>
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>

        <div id="show-field" class="modal" tabindex="-1">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">
                        Field Preview
                    </h4>
                    </div>
                    <div class="modal-body">
                        <textarea readonly class="form-control">{{fieldPreview}}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div id="connection-settings" class="modal" tabindex="-1">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">
                        Connection Settings
                    </h4>
                    </div>
                    <div class="modal-body">
                        <p>
                            <label>Cookie String</label>
                            <input type="text" class="form-control" v-model="connection.cookie">
                        </p>

                        <p>
                            <label>User Agent</label>
                            <input type="text" class="form-control" v-model="connection.user_agent">
                        </p>

                        <p>
                            <label>Proxy IP:PORT</label>
                            <input type="text" class="form-control" v-model="connection.proxy">
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button v-on:click="saveConnectionSettings()" type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>

        <div id="save-form" class="modal" tabindex="-1">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <ul class="nav nav-tabs modal-tabs">
                            <li v-on:click="settingsTab = 'task'" :class="{'active' : settingsTab == 'task'}"><a href="javascript:;"><i class="fas fa-wrench"></i> General</a></li>

                            <li v-on:click="settingsTab = 'schedule'" :class="{'active' : settingsTab == 'schedule'}"><a href="javascript:;"><i class="fas fa-clock"></i> Schedule</a></li>

                            <li v-on:click="settingsTab = 'track-changes'" :class="{'active' : settingsTab == 'track-changes'}"><a href="javascript:;"><i class="fas fa-tasks"></i> Track Changes</a></li>

                            <li v-on:click="settingsTab = 'attachment'" :class="{'active' : settingsTab == 'attachment'}"><a href="javascript:;"><i class="fas  fa-paperclip"></i> Attachments</a></li>

                            <li v-on:click="settingsTab = 'limit'" :class="{'active' : settingsTab == 'limit'}"><a href="javascript:;"><i class="fas fa-indent"></i> Limits</a></li>

                            <li v-on:click="settingsTab = 'conditions'" :class="{'active' : settingsTab == 'conditions'}"><a href="javascript:;"><i class="fas fa-random"></i> Conditions</a></li>
                        </ul>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="section" v-show="settingsTab == 'task'">
                                <div class="col-md-6">
                                    <h4>Task Name</h4>

                                    <input type="text" class="form-control" placeholder="My automated task" v-model="form.name">

                                    <hr>

                                    <h4>Categories</h4>

                                    <div class="input-group">
                                        <div class="input-group-addon">Category</div>
                                        <select multiple class="form-control" v-model="form.categoryIds">
                                            <option v-show="category.taxonomy == form.postType" :value="category.id" v-for="category in categories">{{category.name}}</option>
                                        </select>
                                    </div>

                                    <div class="alert alert-info" v-show="checkCategoryField()">
                                        There is already post_category set in data fields, categories will be created by plugin. You could still select additional categories from list.
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h4>Status</h4>

                                    <div class="input-group">
                                        <div class="input-group-addon">Status</div>
                                        <select class="form-control" v-model="form.postStatus">
                                            <option value="draft">Draft</option>
                                            <option value="publish">Publish</option>
                                        </select>
                                    </div>

                                    <hr>
                                    <h4>Type</h4>

                                    <div class="input-group">
                                        <div class="input-group-addon">Type</div>
                                        <select class="form-control" v-model="form.postType">
                                            <option :value="type" v-for="type in postTypes">{{type}}</option>
                                        </select>
                                    </div>

                                    <hr>

                                    <h4>Post Format</h4>

                                    <div class="input-group">
                                        <div class="input-group-addon">Format</div>
                                        <select class="form-control" v-model="form.other.postFormat">
                                            <option :value="format.value" v-for="format in postFormats">{{format.label}}</option>
                                        </select>
                                    </div>

                                    <div v-show="form.singlePost">
                                        <hr>

                                        <h4>Task Process Method</h4>

                                        <div class="input-group">
                                            <div class="input-group-addon">Select Post</div>
                                            <select class="form-control" v-model="form.postUpdate">
                                                <option value="-1">No Update (Create New Post)</option>
                                                <option value="0">Update and create shortcodes</option>
                                                <option v-if="checkPostType(post, form.postType)" :value="post.id" v-for="post in latestPosts">{{post.name}}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="section" v-show="settingsTab == 'schedule'">
                                <div class="col-md-6">
                                    <h4>Schedule</h4>
                                    <p>Triggers task with defined interval.</p>

                                    <div class="input-group">
                                        <div class="input-group-addon">Interval</div>
                                        <select class="form-control" v-model="form.runInterval">
                                            <?php
                                                $intervals = array(
                                                    '0'  => 'Not defined',
                                                    '1'  => 'Every minute',
                                                    '16' => 'Every 2 minutes',
                                                    '2'  => 'Every 5 minutes',
                                                    '3'  => 'Every 10 minutes',
                                                    '4'  => 'Every 15 minutes',
                                                    '5'  => 'Every 30 minutes',
                                                    '6'  => 'Every hour',
                                                    '7'  => 'Every 3 hours',
                                                    '8'  => 'Every 6 hours',
                                                    '9'  => 'Every 12 hours',
                                                    '10'  => 'Every day',
                                                    '11' => 'Every 2 days',
                                                    '12' => 'Every 3 days',
                                                    '13' => 'Weekly',
                                                    '14' => 'Every 2 weeks',
                                                    '15' => 'Monthly'
                                                );

                                                foreach ($intervals as $key => $value) {
                                                    echo '<option value="'.$key.'">'.$value.'</option>';
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h4>Process Delay</h4>
                                    <p>Defines a delay between each URL process.</p>

                                    <div class="input-group">
                                        <div class="input-group-addon">Delay</div>
                                        <select class="form-control" v-model="form.runDelay">
                                            <?php
                                                $delays = array(
                                                    '0'  => 'Not defined',
                                                    '1'  => '1 seconds',
                                                    '2'  => '5 seconds',
                                                    '7'  => '10 seconds',
                                                    '3'  => '30 seconds',
                                                    '4'  => '1 minute',
                                                    '5'  => '5 minute',
                                                    '6'  => '10 minute',
                                                );

                                                foreach ($delays as $key => $value) {
                                                    echo '<option value="'.$key.'">'.$value.'</option>';
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="section" v-show="settingsTab == 'track-changes'">
                                <div class="col-md-6">
                                    <h4>Track Changes</h4>
                                    <p>
                                        <label>
                                            <input type="checkbox" v-model="form.trackChanges" value="1">
                                            Check posts by title and update after changes
                                        </label>
                                    </p>

                                    <p>
                                        <label>
                                            <input type="checkbox" v-model="form.resetTask" value="1">
                                            Automatically resets the task when it's done.
                                        </label>
                                    </p>

                                    <p>
                                        <label>
                                            <input type="checkbox" v-model="form.deletePost" value="1">
                                            Check if source post is deleted and perform function.
                                        </label>
                                    </p>

                                    <p v-show="form.deletePost">
                                        <label>Perform this function on 404 status :</label>
                                        <select class="form-control" v-model="form.deleteMethod">
                                            <option value="delete">Delete post</option>
                                            <option value="status_draft">Change status to : draft</option>
                                            <option value="status_publish">Change status to : publish</option>
                                        </select>
                                    </p>

                                    <p>
                                        <label>
                                            <input type="checkbox" v-model="form.other.noStatusChange" value="1">
                                            Don't change status on task updates.
                                        </label>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <h4>Uniqueness Check Method</h4>

                                    <div class="input-group">
                                        <div class="input-group-addon">Method</div>
                                        <select class="form-control" v-model="form.uniquenessMethod">
                                            <option value="none">None</option>
                                            <option value="URL">URL</option>
                                            <option value="post_title">Post Title</option>
                                            <option value="product_sku">Product SKU</option>
                                        </select>
                                    </div>

                                    <p>
                                        <label>
                                            <input type="checkbox" v-model="connection.ignore_params">
                                            Ignore URL parameters on URL uniqueness.
                                        </label>
                                    </p>
                                </div>
                            </div>


                            <div class="section" v-show="settingsTab == 'attachment'">
                                <div class="col-md-6">
                                    <h4>Filename Template</h4>

                                    <div class="input-group">
                                        <div class="input-group-addon">Filename</div>
                                        <input type="text" v-model="form.filename" class="form-control">
                                    </div>

                                    <p class="tags-container">Available Tags : <br>
                                        <small>
                                            <b v-html="'{{index}}'"></b>
                                            <b v-html="'{{hash}}'"></b>
                                            <b v-html="'{{random}}'"></b>
                                            <b v-html="'{{originalname}}'"></b>
                                            <b v-html="'{{post_title}}'"></b>
                                        </small>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <h4>Attachment Options</h4>
                                    <p>
                                        <label>
                                            <input type="checkbox" v-model="form.downloadImages" value="1">
                                            Download images or attachments to media library
                                        </label>
                                    </p>
                                </div>
                            </div>

                            <div class="section" v-show="settingsTab == 'limit'">
                                <div class="col-md-6">
                                    <h4>Loop Limit</h4>

                                    <p>On each trigger, plugin processes URLs up to defined limit.</p>

                                    <div class="input-group">
                                        <div class="input-group-addon">Loop Limit</div>
                                        <input type="number" class="form-control" v-model="form.taskLimit" placeholder="0">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h4>Total Run</h4>

                                    <p>The task stops completely when task reaches this limit.</p>

                                    <div class="input-group">
                                        <div class="input-group-addon">Maximum Limit</div>
                                        <input type="number" class="form-control" v-model="connection.total_run" placeholder="0">
                                    </div>
                                </div>
                            </div>

                            <div class="section" v-show="settingsTab == 'conditions'">
                                <div class="col-md-6">
                                    <h4>Exclude Post</h4>

                                    <p>This method checks fields and if it contains any tags defined for excludition process, it excludes posts from process.</p>

                                    <div class="input-group">
                                        <div class="input-group-addon">Field</div>
                                        <select class="form-control" v-model="form.excludeField">
                                            <option v-if="field.type == 'post_title' || field.type == 'post_content' || field.type == 'variable' || field.type == 'post_category'" v-for="field in form.fields" :value="field.type + ':' + field.name">{{field.type + ' - ' + field.name}}</option>
                                        </select>
                                    </div>

                                    <div class="input-group">
                                        <div class="input-group-addon">Tags with commas</div>
                                        <input type="text" class="form-control" v-model="form.excludeTags" placeholder="example, sample">
                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <h4>Special Conditions</h4>

                                    <p>This excludes tasks with special condition expressions.</p>

                                    <div class="input-group">
                                        <div class="input-group-addon"><i class="fas fa-code"></i> Expression</div>
                                        <input type="text" class="form-control" v-model="form.taskCondition" placeholder="price > 5">
                                    </div>

                                    <p>Please see our <a target="_blank" href="https://scraper.site/documentation/index.php/special-conditions-for-products/">documentation</a> for condition expressions.</p>
                                </div>
                            </div>

                            <div class="col-md-6 scraper-hidden">
                                <h4>Task Name</h4>

                                <input type="text" class="form-control" placeholder="My automated task" v-model="form.name">

                                <hr>

                                <h4>Post Category</h4>

                                <div class="input-group">
                                    <div class="input-group-addon">Category</div>
                                    <select multiple class="form-control" v-model="form.categoryIds">
                                        <option v-show="category.taxonomy == form.postType" :value="category.id" v-for="category in categories">{{category.name}}</option>
                                    </select>
                                </div>

                                <hr>

                                <h4>Options</h4>

                                <p>
                                    <label>
                                        <input type="checkbox" v-model="form.downloadImages" value="1">
                                        Download images to media library
                                    </label>
                                </p>

                                <p>
                                    <label>
                                        <input type="checkbox" v-model="form.trackChanges" value="1">
                                        Check posts by title and update after changes
                                    </label>
                                </p>

                                <p>
                                    <label>
                                        <input type="checkbox" v-model="form.resetTask" value="1">
                                        Automatically resets the task when it's done.
                                    </label>
                                </p>

                                <p>
                                    <label>
                                        <input type="checkbox" v-model="form.deletePost" value="1">
                                        Check if source post is deleted and perform function.
                                    </label>
                                </p>

                                <p v-show="form.deletePost">
                                    <label>Perform this function on 404 status :</label>
                                    <select class="form-control" v-model="form.deleteMethod">
                                        <option value="delete">Delete post</option>
                                        <option value="status_draft">Change status to : draft</option>
                                        <option value="status_publish">Change status to : publish</option>
                                    </select>
                                </p>

                                <p>
                                    <label>
                                        <input type="checkbox" v-model="form.publicTask" value="1">
                                        Share on community templates
                                    </label>
                                </p>

                                <hr>
                                <h4>Exclude Post</h4>

                                <p>It excludes posts if it contains any tag entered below.</p>
                                <div class="input-group">
                                    <div class="input-group-addon">Tags with commas</div>
                                    <input type="text" class="form-control" v-model="form.excludeTags" placeholder="example, sample">
                                </div>
                            </div>
                            <div class="col-md-6 scraper-hidden">
                                <h4>Schedule</h4>
                                <p>Triggers task with defined interval.</p>

                                <div class="input-group">
                                    <div class="input-group-addon">Interval</div>
                                    <select class="form-control" v-model="form.runInterval">
                                        <?php
                                            $intervals = array(
                                                '0'  => 'Not defined',
                                                '1'  => 'Every minute',
                                                '16' => 'Every 2 minutes',
                                                '2'  => 'Every 5 minutes',
                                                '3'  => 'Every 10 minutes',
                                                '4'  => 'Every 15 minutes',
                                                '5'  => 'Every 30 minutes',
                                                '6'  => 'Every hour',
                                                '7'  => 'Every 3 hours',
                                                '8'  => 'Every 6 hours',
                                                '9'  => 'Every 12 hours',
                                                '10'  => 'Every day',
                                                '11' => 'Every 2 days',
                                                '12' => 'Every 3 days',
                                                '13' => 'Weekly',
                                                '14' => 'Every 2 weeks',
                                                '15' => 'Monthly'
                                            );

                                            foreach ($intervals as $key => $value) {
                                                echo '<option value="'.$key.'">'.$value.'</option>';
                                            }
                                        ?>
                                    </select>
                                </div>

                                <hr>

                                <h4>Process Delay</h4>
                                <p>Defines a delay between each URL process.</p>

                                <div class="input-group">
                                    <div class="input-group-addon">Delay</div>
                                    <select class="form-control" v-model="form.runDelay">
                                        <?php
                                            $delays = array(
                                                '0'  => 'Not defined',
                                                '1'  => '1 seconds',
                                                '2'  => '5 seconds',
                                                '7'  => '10 seconds',
                                                '3'  => '30 seconds',
                                                '4'  => '1 minute',
                                                '5'  => '5 minute',
                                                '6'  => '10 minute',
                                            );

                                            foreach ($delays as $key => $value) {
                                                echo '<option value="'.$key.'">'.$value.'</option>';
                                            }
                                        ?>
                                    </select>
                                </div>

                                <hr>
                                <h4>Publishing</h4>

                                <div class="input-group">
                                    <div class="input-group-addon">Status</div>
                                    <select class="form-control" v-model="form.postStatus">
                                        <option value="draft">Draft</option>
                                        <option value="publish">Publish</option>
                                    </select>
                                </div>

                                <hr>
                                <h4>Post Type</h4>

                                <div class="input-group">
                                    <div class="input-group-addon">Type</div>
                                    <select class="form-control" v-model="form.postType">
                                        <option :value="type" v-for="type in postTypes">{{type}}</option>
                                    </select>
                                </div>

                                <hr>
                                <h4>Uniqueness Check Method</h4>

                                <div class="input-group">
                                    <div class="input-group-addon">Method</div>
                                    <select class="form-control" v-model="form.uniquenessMethod">
                                        <option value="none">None</option>
                                        <option value="URL">URL</option>
                                        <option value="post_title">Post Title</option>
                                        <option value="product_sku">Product SKU</option>
                                    </select>
                                </div>

                                <div v-show="form.singlePost">
                                    <hr>

                                    <h4>Update a post</h4>

                                    <div class="input-group">
                                        <div class="input-group-addon">Select Post</div>
                                        <select class="form-control" v-model="form.postUpdate">
                                            <option value="-1">No Update (Create New Post)</option>
                                            <option :value="post.id" v-for="post in latestPosts">{{post.name}}</option>
                                        </select>
                                    </div>
                                </div>

                                <hr>
                                <h4>Filename Template</h4>

                                <div class="input-group">
                                    <div class="input-group-addon">Filename</div>
                                    <input type="text" v-model="form.filename" class="form-control">
                                </div>

                                <p class="tags-container">Available Tags : <br>
                                    <small>
                                        <b v-html="'{{index}}'"></b>
                                        <b v-html="'{{hash}}'"></b>
                                        <b v-html="'{{random}}'"></b>
                                        <b v-html="'{{originalname}}'"></b>
                                        <b v-html="'{{post_title}}'"></b>
                                    </small>
                                </p>

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <span class="pull-left save-warning scraper-hidden">
                            <i class="fas fa-exclamation-triangle"></i>
                            Your task doesn't have schedule.
                        </span>
                        <button v-on:click="saveProject()" type="button" class="btn btn-default"><i class="fas fa-save"></i> Save and Goto Tasks</button>
                        <?php if(isset($_GET['hash']) && $_GET['hash'] != ''){ ?>
                            <button v-on:click="saveProject('','closeModel');" type="button" class="btn btn-primary"><i class="fas fa-save"></i> Save and Close</button>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>

        <?php
            if($token == 'live'){
        ?>
        <nav class="navbar navbar-default custom-navbar">
    <div class="custom-container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand custom-brand" href="https://scraper.piktd.com/"><img src="https://scraper.piktd.com/aes_website/img/deepcrawllogo.svg"  style="margin-top:15px;width:200px !important;" alt=""></a>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav custom-nav navbar-right">
                <li><a data-scroll href="https://scraper.piktd.com/">Home</a></li>
                <li><a data-scroll href="https://scraper.piktd.com/#features">Feature</a></li>
                <li><a data-scroll href="https://scraper.piktd.com/#howitsworks">How It works?</a></li>
                <li><a data-scroll href="https://scraper.piktd.com/scrapper">Scrapper</a></li>
                <li><a href="https://scraper.piktd.com/home/sign_up">Sign Up</a></li>
                <li><a target="_blank" href="https://scraper.piktd.com/home/login_page">Log In</a></li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container -->
</nav>
        <?php
            }
        ?>

        <div id="frame-actions">
            <div class="row">
                <div class="col-md-8">
                    <form v-on:submit="fetch()" action="javascript:;" method="POST" class="iframe-form">
                        <label>Page URL</label>

                        <div class="input-group">
                            <input type="text" class="form-control url-input-group" v-model="form.URL" placeholder="http(s)://">                            
                          
                            <span class="input-group-btn">

                                <select :disabled="loading" class="form-control scrape-single-multiple-post" v-on:change="updatePostMethod()" v-model="form.singlePost">
                                    <option value="">Multiple Post</option>
                                    <option value="1">Single Post</option>
                                </select>  
                                <button type="button" v-on:click="showBulkURLs()" v-show="form.singlePost" class="btn btn-default"><i class="fas fa-list"></i> Bulk URL List</button>
                                <button :disabled="loading" type="submit" class="btn btn-success">Fetch</button>
                                <button :disabled="loading" type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="caret"></span>
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu pull-right">
                                    <li><a href="javascript:;" v-on:click="showSettings()">Proxy Settings</a></li>
                                    <li><a href="javascript:;" v-on:click="showSettings()">Cookies and user agent</a></li>
                                </ul>
                            </span>
                        </div>
                    </form>
                   
                </div>
                <div class="col-md-4 button-margin-fix">
                    <button v-on:click="showLibrary()" class="btn btn-link mini-hide"><i class="fas fa-book"></i> Template Library</button>
                    <?php
                        if($token != 'demo'){
                    ?>

                    <div class="btn-group pull-right" role="group">
                        <button :disabled="loading || !form.URL" v-on:click="runScrapingModel()" class="btn btn-primary ">
                            <i class="fas fa-bolt"></i> Scrape
                        </button>
                        <button :disabled="loading || !form.URL" v-on:click="openSaveModal()" class="btn btn-primary" title="Save Project">
                            <span ><i class="fas fa-save"></i> Save</span>                            
                        </button>
                        <button :disabled="loading || !form.URL" v-on:click="openSaveAsModal()" class="btn btn-primary pull-right" title="Clone Project">                            
                            <span ><i class="far fa-clone"></i></span>                            
                        </button>                      
                    </div>

                    <?php
                        }else{
                    ?>
                    <button :disabled="loading || !form.URL" v-on:click="runScrapingModel()" class="btn btn-primary pull-right">
                        <span v-show="!loading"><i class="fas fa-bolt"></i> Run Scraping Model</span>
                        <span v-show="loading">Running...</span>
                    </button>
                    <?php
                        }
                    ?>
                </div>
            </div>
        </div>

        <div id="content">
            <div v-show="loading" class="loading-overlay">

            </div>
            <div class="row">
                <div class="col-md-8">
                    <ul class="nav nav-tabs">
                        <li v-if="wizard.enabled" :class="{'active' : form.frame == 'wizard' }" v-on:click="form.frame = 'wizard'"><a href="javascript:;"><i class="fas fa-magic"></i> Wizard</a></li>

                        <li v-show="!form.singlePost" :class="{'active' : form.frame == 'feed' }" v-on:click="form.frame = 'feed'"><a href="javascript:;"><b>1.</b> Post Items</a></li>
                        <li :class="{'active' : form.frame == 'content' }" v-on:click="form.frame = 'content'"><a href="javascript:;"><b>2.</b> Post Content</a></li>
                        <li :class="{'active' : form.frame == 'preview' }" v-on:click="form.frame = 'preview'"><a href="javascript:;">Preview Results</a></li>

                        <li v-show="form.frame == 'content' || form.frame == 'feed'" v-on:click="zoomOut()" class="pull-right"><a href="javascript:;"><i class="fas fa-search-minus"></i></a></li>
                        <li v-show="form.frame == 'content' || form.frame == 'feed'" v-on:click="zoomIn()" class="pull-right"><a href="javascript:;"><i class="fas fa-search-plus"></i></a></li>
                        <li v-show="form.frame == 'content' || form.frame == 'feed'" v-on:click="fetch(true)" class="pull-right"><a href="javascript:;"><i class="fas fa-sync-alt"></i></a></li>
                        <li v-show="form.frame == 'content' || form.frame == 'feed'" v-on:click="removeElement()" class="pull-right"><a href="javascript:;"><i class="fas fa-eraser"></i> Remove Element</a></li>
                    </ul>
                    <div class="responsive-frame">
                        <div v-show="form.frame == 'wizard'">
                            <div class="alert alert-info">We detected some fields from this page. You could use them on your task. These fields detected by Scraper, they might be wrong for some cases. <b>Please make sure they are correct before going production.</b></div>

                            <table class="table table-striped">
                                <tr>
                                    <th width="150">Field</th>
                                    <th>Sample Content</th>
                                    <th>Xpath</th>
                                    <th width="100">Actions</th>
                                </tr>

                                <tr v-for="item in wizard.result">
                                    <td>{{item.type}}</td>
                                    <td>
                                        <div v-if="item.type != 'featured_image'">
                                            <span>{{stringClip(item.sample, 30)}}</span>
                                            <br>
                                            <small><a v-on:click="showField(item.sample, item.type)" href="javascript:;">Show More</a></small>
                                        </div>

                                        <img class="mini-result-image" v-if="item.type == 'featured_image'" :src="item.sample">
                                    </td>
                                    <td>{{item.xpath}}</td>
                                    <td>
                                        <button class="btn btn-sm btn-primary"><i class="fas fa-crosshairs"></i> Use Data Field</button>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <iframe v-show="form.frame == 'feed' && !form.singlePost" id="visual-editor" :src="result.feedURL"></iframe>

                        <iframe v-show="(form.frame == 'content' && result.contentURL)" id="content-editor" :src="result.contentURL"></iframe>

                        <div v-show="form.frame == 'preview'">
                            <p>You could test your scraping model on this section. It will list all fields and URL's on this page. To see preview click "Scrape" above.</p>
                            

                            <button v-show="preview.next_page" v-on:click="runScrapingModel(true)" class="btn btn-default">Next Page ({{temp.currentPage + 1}}) <i class="fas fa-caret-right"></i></button>
                            <hr>
                            <div v-if="preview.items.length > 0" class="table-responsive">
                                <table class="table table-striped">
                                    <tr>
                                        <th width="30">#</th>
                                        <th width="150">Post URL</th>
                                        <th v-for="field in preview.fields">{{field}}</th>
                                    </tr>

                                    <tr v-if="item != false" v-for="(item, $index) in preview.items">
                                        <td>{{$index+1}}</td>
                                        <td><a target="_blank" :href="item.URL">{{limitString(item.URL)}}</a></td>
                                        <td v-for="field in preview.fields">
                                            <div v-if="item.post[field]">
                                                <img class="mini-result-image" v-if="field == 'featured_image' || field == 'image'" :src="item.post[field]">

                                                <img class="mini-result-image" v-for="image in item.post[field]" v-if="field == 'gallery'" :src="image">

                                                <div v-if="item.post[field]" v-show="field != 'featured_image' && field != 'image' && field != 'gallery'">
                                                    <span>{{stringClip(item.post[field], 30)}}</span>
                                                    <br>
                                                    <small><a v-on:click="showField(item.post[field], field)" href="javascript:;">Show More</a></small>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <div class="alert alert-info"><i class="fas fa-exclamation-triangle"></i> We provide maximum of 8 results in test run mode, save the project for live run.</div>
                            <button v-on:click='downloadCSVFunction(preview.fields, preview.items)' class="btn btn-success downloda-csv">Download as CSV</button>
                        </div>
                        <div v-show="form.frame == 'content' && !result.contentURL" class="centerized-info">
                            <p>Please select serial item link or enter content link first!</p>
                            <p>Go to Feed section, select pick tool for serial item on sidebar, select your feed post's link.</p>
                        </div>
                    </div>
                    <div v-show="form.frame == 'content' || form.frame == 'feed'" class="responsive-query">
                        <div class="input-group">
							<input type="type" class="form-control input-sm pull-left" v-model="temp.command" placeholder="Enter Command" v-on:keyup.enter="runCommand()">
							<span class="input-group-btn">
								<button class="btn btn-default btn-sm" v-on:click="runCommand()" type="button">Exec</button>
							</span>
						</div>

                        <div class="view-controllers">
                            <span>Parse Method : </span>
                            <a :class="{'active' : viewMethod == 'HTML'}" v-on:click="switchView('HTML')" href="javascript:;">HTML</a>
                            <a :class="{'active' : viewMethod == 'META'}" v-on:click="switchView('META')" href="javascript:;">Meta Tags</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div v-show="form.frame == 'feed'" class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fas fa-list-ol"></i> Serial Items
                        </div>
                        <div class="panel-body">
                            <div v-show="!form.singlePost">
                                <div class="alert alert-info">
                                    Click <i class="fas fa-crosshairs"></i> button to connect element to Scraper data field.
                                </div>

                                <p>Serial items are the sequential links of the contents. They should be specified to find content.</p>
                                <div class="input-group">
                                    <div class="input-group-addon">Item's Path</div>
                                    <input type="text" class="form-control" v-on:change="applyPath(form.feed, 'feed', true)" v-model="form.feed.path" :placeholder="form.parseMethod == 'xpath' ? 'Enter XPath' : 'Enter Regex Expression'">
                                    <span class="input-group-btn">
                                        <button v-show="form.parseMethod == 'xpath'" v-on:click="selectPath(form.feed, true, 'href')" class="btn btn-primary" :class="{'btn-default' : form.feed.selecting}" type="button">Select <i class="fas fa-crosshairs"></i></button>
                                    </span>
                                </div>

                                <div class="input-group" v-show="form.feed.siblings">
                                    <div class="input-group-addon">Item Count</div>
                                    <input type="text" readonly class="form-control" :value="form.feed.siblings.length">
                                </div>

                                <div class="input-group" v-show="form.feed.siblings">
                                    <div class="input-group-addon">Samples</div>
                                    <select v-model="redirectionSamples" v-on:change="redirectPath()" class="form-control">
                                        <option v-if="form.feed.siblings.length == 0" value="">No sample found</option>
                                        <option :value="siblingKey" v-for="(sibling, siblingKey) in form.feed.siblings">{{getSampleContent(sibling)}}</option>
                                    </select>
                                    <div class="input-group-btn">
                                        <button v-on:click="form.frame = 'content'" class="btn btn-default">Jump to content</button>
                                    </div>
                                </div>
                            </div>
                            <div v-show="form.singlePost" class="centerized-info">
                                It's single post, please only select post content fields.
                            </div>
                        </div>
                    </div>

                    <div v-if="form.nextPage" v-show="form.frame == 'feed' && !form.singlePost" class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fas fa-arrow-right"></i> Next Page (Pagination)
                        </div>
                        <div class="panel-body">
                            <p>This feature scans next pages and continues to fetch with pagination. It only works if there is <b>"next page"</b> button.</p>
                            <div class="input-group">
                                <div class="input-group-addon">Next Page Button</div>
                                <input type="text" class="form-control" v-on:change="applyPath(form.nextPage, 'content', true)" v-model="form.nextPage.path" :placeholder="form.parseMethod == 'xpath' ? 'Enter XPath' : 'Enter Regex Expression'">
                                <span class="input-group-btn">                                    
                                    <button v-show="form.parseMethod == 'xpath'" v-on:click="selectPath(form.nextPage, false, 'next_page')" class="btn btn-default" :class="{'btn-primary' : form.nextPage.selecting}" type="button">Select <i class="fas fa-crosshairs"></i></button>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div v-show="form.frame == 'content'" class="panel panel-default data-fields">
                        <div class="panel-heading">
                            <i class="fas fa-file-export"></i> Post Fields
                            <label class="pull-right">
                                <input type="checkbox" value="true" v-model="advanceMode">
                                Advance Mode
                            </label>
                        </div>
                        <div v-show="advanceMode == true" class="panel-body scrollable-content">
                            <div v-for="field in form.fields" class="panel panel-default">
                                <div v-on:click="field.display = !field.display" class="panel-heading field-header">
                                    {{field.type == 'variable' ? field.name : field.type}}
                                    <a class="pull-right" href="javascript:;">
                                        <i class="fas fa-caret-down"></i>
                                    </a>
                                </div>
                                <div v-show="field.display" class="panel-body">
                                    <div class="input-group">
                                        <div class="input-group-addon">Type</div>
                                        <select v-on:change="updateTypeMethods(field)" v-model="field.type" class="form-control">
                                            <option value="post_title">Post Title (post_title)</option>
                                            <option value="post_content">Post Content (post_content)</option>
                                            <option value="post_excerpt">Post Excerpt (post_excerpt)</option>
                                            <option value="featured_image">Featured Image</option>
                                            <option value="tags_input">Tags (tag)</option>
                                            <option value="post_date">Post Date (post_date)</option>
                                            <option value="post_category">Post Category (category)</option>
                                            <option value="image">Image (Media Library)</option>
                                            <option value="downloaded_file">Download File</option>
                                            <option value="gallery">Gallery (Serial Images)</option>
                                            <option value="variable">Custom Variable (variable)</option>
                                            <option value="post_author">Post Author (post_author)</option>
                                            <option value="post_slug">Post Slug (post_slug)</option>
                                            <option value="shortcode">Shortcode Variable (variable)</option>
                                            <option value="product_tag">Product Tags (product_tag)</option>
                                            <option value="product_cat">Product Categories (product_cat)</option>
                                            <option value="product_attributes">Product Attribute</option>
                                            <option v-for="customField in customFields">{{customField.name}}</option>
                                        </select>
                                    </div>

                                    <div v-show="field.type == 'shortcode' || field.type == 'variable' || field.type == 'image' || field.type == 'downloaded_file'" class="input-group">
                                        <div class="input-group-addon">Shortcode</div>
                                        <input type="text" class="form-control" v-model="field.name">
                                    </div>

                                    <div v-show="field.type == '_product_attributes'" class="input-group">
                                        <div class="input-group-addon">Name</div>
                                        <input type="text" class="form-control" v-model="field.name">
                                    </div>

                                    <div v-show="field.type == 'tags_input'" class="input-group">
                                        <div class="input-group-addon">Taxonomy</div>
                                        <input type="text" class="form-control" v-model="field.name">
                                    </div>

                                    <div v-show="field.type == 'image'" class="input-group">
                                        <div class="input-group-addon">Extract</div>
                                        <select v-model="field.extract" class="form-control">
                                            <option value="html">HTML</option>
                                            <option value="url">URL</option>
                                            <option value="id">ID</option>
                                        </select>
                                    </div>

                                    <div v-show="field.type != 'shortcode' && field.type != 'post_title' && field.type != 'post_content' && field.type != 'featured_image' && field.type != 'post_excerpt' && field.type != 'post_date' && field.type != 'post_category' && field.type != 'image' && field.type != 'post_slug' && field.type != 'post_author' && field.type != 'featured_image'" class="input-group">
                                        <div class="input-group-addon">Is Multiple?</div>
                                        <span class="input-checkbox">
                                            <input type="checkbox" v-model="field.isMultiple" value="true">
                                        </span>
                                    </div>

                                    <div v-show="field.type != 'post_author'" class="input-group">
                                        <div class="input-group-addon">Required</div>
                                        <span class="input-checkbox">
                                            <input type="checkbox" v-model="field.isRequired" value="true">
                                        </span>
                                    </div>

                                    <div v-if="field.type == 'post_author'" class="input-group">
                                        <div class="input-group-addon">Accounts</div>
                                        <select v-model="field.content" class="form-control">
                                            <option v-for="account in temp.accounts" :value="account.ID">{{account.display_name}}</option>
                                        </select>
                                    </div>

                                    <hr>

                                    <div class="input-group" v-show="field.type != 'post_author'">
                                        <div class="input-group-addon">Path <span v-show="form.parseMethod == 'regex'">({{field.regexIndex}})</span></div>
                                        <input type="text" class="form-control" v-on:change="applyPath(field, 'content', false)" v-model="field.path" :placeholder="form.parseMethod == 'xpath' ? 'Enter XPath' : 'Enter Regex Expression'">

                                        <span class="input-group-btn" v-show="form.parseMethod == 'regex'">
                                            <button v-on:click="regexIndexDown(field)" class="btn btn-default" type="button"><i class="fas fa-angle-up"></i></button>

                                            <button v-on:click="regexIndexUp(field)" class="btn btn-default" type="button"><i class="fas fa-angle-down"></i></button>
                                        </span>

                                        <span class="input-group-btn">
                                            <button v-show="form.parseMethod == 'xpath'" v-on:click="selectPath(field, (field.type == 'post_category' || field.type == 'tags_input' || field.type == 'gallery' || field.isMultiple ? true : false), getPathMethod(field.type))" class="btn btn-primary" :class="{'btn-default' : form.feed.selecting}" type="button">Select <i class="fas fa-crosshairs"></i></button>
                                        </span>
                                    </div>

                                    <div class="input-group" v-if="form.parseMethod == 'xpath'" v-show="field.type != 'post_author'">
                                        <div class="input-group-addon">Part</div>
                                        <select v-model="field.prop" class="form-control">
                                            <option value="innerHTML">HTML Source Code</option>
                                            <option value="innerText">Text Content (No HTML Tags)</option>
                                            <option v-for="prop in getProps(field.element)" :value="prop.label">{{prop.label}} - {{prop.sample}}</option>
                                        </select>
                                    </div>

                                    <div class="input-group">
                                        <div class="input-group-addon">Sample</div>
                                        <input type="text" readonly class="form-control" :value="getSampleContent(field.element, field.prop, field)" placeholder="No sample found">
                                        <a :href="getSampleContent(field.element, field.prop, field)" target="_blank" v-show="field.type == 'image' || field.type == 'featured_image' || field.type == 'gallery'" class="input-group-addon sample-image">
                                            <img class="" :src="getSampleContent(field.element, field.prop, field)">
                                        </a>
                                    </div>

                                    <div v-show="field.type == 'post_category' || field.type == 'tags_input' || field.type == 'gallery' || field.isMultiple" class="input-group">
                                        <div class="input-group-addon">Count</div>
                                        <input type="text" readonly class="form-control" :value="getSampleCount(field)">
                                    </div>

                                    <div class="component-field" v-show="field.type == 'shortcode'">
                                        <hr>

                                        <h5>Wordpress Shortcode</h5>

                                        <p>You could use this variable with saving this task on shortcode method.</p>

                                        <code v-show="form.hash">[scraper_shortcode task="{{form.hash}}" key="{{field.name}}"]</code>
                                        <div class="alert alert-info" v-show="!form.hash">You need to save task to get shortcode. Save task and click update button to see shortcode.</div>
                                    </div>

                                    <div class="component-field" v-show="field.type == 'gallery'">
                                        <hr>

                                        <h5><b>Actions</b></h5>

                                        <p>Gallery shortcode can be applied with <code v-html="'{{gallery}}'"></code>, but you could also append it to content field with <a href="javascript:;" v-on:click="appendGalleryShortCode()">clicking here</a>.</p>

                                        <hr>

                                        <h5><b>Gallery Settings</b></h5>

                                        <div class="input-group">
                                            <div class="input-group-addon">Columns</div>
                                            <select v-model="field.galleryColumns" class="form-control">
                                                <option value="0">0</option>
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                            </select>
                                        </div>

                                        <div class="input-group">
                                            <div class="input-group-addon">Size</div>
                                            <select v-model="field.gallerySize" class="form-control">
                                                <option value="thumbnail">thumbnail</option>
                                                <option value="medium">medium</option>
                                                <option value="large">large</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div v-show="field.display" class="panel-footer">
                                    <button v-on:click="showTransform(field)" class="btn btn-default"><i class="fas fa-random"></i> Transform</button>
                                    <button v-on:click="removePath(field)" class="btn btn-danger pull-right"><i class="fas fa-times"></i> Remove</button>
                                </div>
                            </div>
                        </div>

                        <div v-show="!advanceMode" class="panel-body scrollable-content">
                            <div v-show="newlyCreatedPost">
                                <p>Please select element from list :</p>
                                <div class="list-group">
                                    <a href="javascript:;" v-on:click="createTemplate('simple-post')" class="list-group-item">
                                        <h4 class="list-group-item-heading"><i class="far fa-newspaper"></i> Select element and value</h4>
                                        <p class="list-group-item-text">Title, content, featured image, gallery, tags</p>
                                    </a>

                                    <a href="javascript:;" v-on:click="createTemplate('simple-product')" class="list-group-item scraper-hidden">
                                        <h4 class="list-group-item-heading"><i class="fas fa-cart-plus"></i> WooCommerce Product</h4>
                                        <p class="list-group-item-text">Title, content, featured image, product gallery, product tags, price, affiliate link, attributes</p>
                                    </a>

                                    <a href="javascript:;" v-on:click="createTemplate('custom-post')" class="list-group-item scraper-hidden">
                                        <h4 class="list-group-item-heading"><i class="fas fa-highlighter"></i> Advance Custom Post</h4>
                                        <p class="list-group-item-text">Customizable data fields</p>
                                    </a>
                                </div>
                            </div>
                            <div v-show="!newlyCreatedPost">
                                <div v-for="field in form.fields" class="simple-field">
                                    <div class="input-group">
                                        <span class="input-group-addon">{{specialNames[field.type == 'tags_input' ? field.name : field.type]}}</span>                                        
                                        <input type="text" class="form-control" v-on:change="applyPath(field, 'content', false)" v-model="field.path" placeholder="Enter Xpath">

                                        <div v-if="suggestedField == field" class="suggestion-box">
                                            <h5>Type a sample to get suggestion</h5>
                                            <li v-on:click="selectElement(item, field)" v-for="item in filterSearch(suggestions, field)">{{item.suggestion.sample}}</li>
                                        </div>

                                        <span class="input-group-btn">
                                            <button :aria-label="specialWarnings[field.type]" v-if="warningCheckField(field)" class="btn btn-warning hint--top"><i class="fas fa-exclamation-triangle"></i></button>
                                            <button aria-label="Transform" v-on:click="showTransform(field)" class="btn btn-default hint--top"><i class="fas fa-random"></i></button>
                                            <button aria-label="Select Element" v-show="form.parseMethod == 'xpath'" v-on:click="selectPath(field, (field.type == 'post_category' || field.type == 'tags_input' || field.type == 'gallery' || field.isMultiple ? true : false), getPathMethod(field.type))" class="btn btn-primary hint--left" :class="{'btn-default' : form.feed.selecting}" type="button"><i class="fas fa-crosshairs"></i></button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-show="!newlyCreatedPost || advanceMode" class="panel-footer">
                            <button v-show="advanceMode" v-on:click="addPath()" class="btn btn-info"><i class="fas fa-plus"></i> Add New Data Field</button>
                            <div v-show="form.fieldsMode == 'simple-product'" class="dropup">
                                <button class="btn btn-default dropdown-toggle" type="button" id="attributeMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Add Product Attributes
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="attributeMenu">
                                    <li v-on:click="addProductAttribute('colors')"><a href="#">Colors</a></li>
                                    <li v-on:click="addProductAttribute('sizes')"><a href="#">Sizes</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/sweetalert.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/vue.min.js"></script>
<script type="text/javascript" src="js/main.js?v=<?php echo $config['version']; ?>"></script>

<?php
    if(isset($_GET['hash'])){
        echo '<script>app.loadTask("'.$_GET['hash'].'");</script>';
    }
?>