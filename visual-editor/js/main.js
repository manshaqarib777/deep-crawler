'use strict';
var app = new Vue({
    el : '#wrapper',
    data : {
        form : {
            hash : '',
            name : '',
            categoryId : 1,
            categoryIds : [1],
            downloadImages : true,
            trackChanges : false,
            resetTask : false,
            deletePost : false,
            deleteMethod : 'delete',
            publicTask : false,
            runInterval : '0',
            runDelay : '0',
            postStatus : 'draft',
            filename : '{{originalname}}',
            postUpdate : -1,
            postType : 'post',
            excludeTags : '',
            excludeField : 'post_title',
            taskCondition : '',
            search : '',
            URL : '',
            feedURL : '',
            contentURL : '',
            frame : 'feed',
            singlePost : '',
            fieldsMode : 'simple-post',
            parseMethod : 'xpath',
            uniquenessMethod : 'URL',
            taskLimit : 0,
            feed : {
                path : '',
                selecting : false,
                samples : [],
                sampleIndex : 0,
                siblings : [],
            },
            nextPage : {
                path : '',
                selecting : false,
                samples : [],
                sampleIndex : 0,
                element : {},
            },
            other : {
                noStatusChange : false,
                postFormat : '0',
                bulkURL : ''
            },
            fields : [
                {
                    name    : 'post_title',
                    type    : 'post_title',
                    path    : '-',
                    prop    : 'innerText',
                    filename: '',
                    element : null,
                    display : false,
                    selecting : false,
                    content : '{{content}}',
                    isRequired : false,
                    extract : 'html',
                    find : '',
                    replace : '',
                    replaces : [
                        {
                            find : '',
                            replace : ''
                        }
                    ],
                    isNumber : false,
                    isMultiple : false,
                    isJSON : false,
                    galleryColumns : '3',
                    gallerySize : 'medium',
                    cleanNonNumerical : false,
                    splitContent : false,
                    splitDelimiter : ',',
                    math : 'value',
                    clipStart : 0,
                    clipEnd : 0,
                    clipWordStart : 0,
                    clipWordEnd : 0,
                    attributeParse : 'none',
                    spinner : false,
                    translate : '',
                    stripTags : false,
                    stripLinks : false,
                    stripAds : false,
                    decodeBitly : false,
                    customContent : false,
                    transform : [],
                    siblings : [],
                    regexIndex : -1,
                    regexResults : []
                }
            ]
        },
        result : {
            data : {},
            feedURL : '',
            contentURL : '',
            success : false
        },
        preview : {
            items : [],
            fields : []
        },
        fieldPreview : '',
        connection : {
            cookie : '',
            user_agent : 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Safari/537.36',
            proxy : '',
            ignore_params : false,
            total_run : 0
        },
        temp : {
            currentPage : 0,
            paginationURL : '',
            startNumber : 0,
            endNumber : 1,
            command : '',
            accounts : []
        },
        wizard : {
            enabled : false,
            result : []
        },
        transformTab : 'find-replace',
        bulkTab : 'url-list',
        redirectionSamples : 0,
        settingsTab : 'task',
        fetchCallback : null,
        loading : false,
        iframe  : {
            'feed' : false,
            'content' : false
        },
        zoom    : 1,
        advanceMode : false,
        newlyCreatedPost : true,
        Scraper : {
            'feed' : false,
            'content' : false
        },
        queryTime : 0,
        startTime : 0,
        enablePathApply : false,
        customFields : [
            { name : 'meta_field' },
            { name : '_price' }
        ],
        selectedField : {},
        viewMethod : 'HTML',
        languages : [
            { label : 'Afrikaans', value : 'af' },
            { label : 'Albanian', value : 'sq' },
            { label : 'Amharic', value : 'am' },
            { label : 'Arabic', value : 'ar' },
            { label : 'Armenian', value : 'hy' },
            { label : 'Azeerbaijani', value : 'az' },
            { label : 'Basque', value : 'eu' },
            { label : 'Belarusian', value : 'be' },
            { label : 'Bengali', value : 'bn' },
            { label : 'Bosnian', value : 'bs' },
            { label : 'Bulgarian', value : 'bg' },
            { label : 'Catalan', value : 'ca' },
            { label : 'Cebuano', value : 'ceb (ISO-639-2)' },
            { label : 'Chinese (Simplified)', value : 'zh-CN (BCP-47)' },
            { label : 'Chinese (Traditional)', value : 'zh-TW (BCP-47)' },
            { label : 'Corsican', value : 'co' },
            { label : 'Croatian', value : 'hr' },
            { label : 'Czech', value : 'cs' },
            { label : 'Danish', value : 'da' },
            { label : 'Dutch', value : 'nl' },
            { label : 'English', value : 'en' },
            { label : 'Esperanto', value : 'eo' },
            { label : 'Estonian', value : 'et' },
            { label : 'Finnish', value : 'fi' },
            { label : 'French', value : 'fr' },
            { label : 'Frisian', value : 'fy' },
            { label : 'Galician', value : 'gl' },
            { label : 'Georgian', value : 'ka' },
            { label : 'German', value : 'de' },
            { label : 'Greek', value : 'el' },
            { label : 'Gujarati', value : 'gu' },
            { label : 'Haitian Creole', value : 'ht' },
            { label : 'Hausa', value : 'ha' },
            { label : 'Hawaiian', value : 'haw (ISO-639-2)' },
            { label : 'Hebrew', value : 'he**' },
            { label : 'Hindi', value : 'hi' },
            { label : 'Hmong', value : 'hmn (ISO-639-2)' },
            { label : 'Hungarian', value : 'hu' },
            { label : 'Icelandic', value : 'is' },
            { label : 'Igbo', value : 'ig' },
            { label : 'Indonesian', value : 'id' },
            { label : 'Irish', value : 'ga' },
            { label : 'Italian', value : 'it' },
            { label : 'Japanese', value : 'ja' },
            { label : 'Javanese', value : 'jw' },
            { label : 'Kannada', value : 'kn' },
            { label : 'Kazakh', value : 'kk' },
            { label : 'Khmer', value : 'km' },
            { label : 'Korean', value : 'ko' },
            { label : 'Kurdish', value : 'ku' },
            { label : 'Kyrgyz', value : 'ky' },
            { label : 'Lao', value : 'lo' },
            { label : 'Latin', value : 'la' },
            { label : 'Latvian', value : 'lv' },
            { label : 'Lithuanian', value : 'lt' },
            { label : 'Luxembourgish', value : 'lb' },
            { label : 'Macedonian', value : 'mk' },
            { label : 'Malagasy', value : 'mg' },
            { label : 'Malay', value : 'ms' },
            { label : 'Malayalam', value : 'ml' },
            { label : 'Maltese', value : 'mt' },
            { label : 'Maori', value : 'mi' },
            { label : 'Marathi', value : 'mr' },
            { label : 'Mongolian', value : 'mn' },
            { label : 'Myanmar (Burmese)', value : 'my' },
            { label : 'Nepali', value : 'ne' },
            { label : 'Norwegian', value : 'no' },
            { label : 'Nyanja (Chichewa)', value : 'ny' },
            { label : 'Pashto', value : 'ps' },
            { label : 'Persian', value : 'fa' },
            { label : 'Polish', value : 'pl' },
            { label : 'Portuguese (Portugal, Brazil)', value : 'pt' },
            { label : 'Punjabi', value : 'pa' },
            { label : 'Romanian', value : 'ro' },
            { label : 'Russian', value : 'ru' },
            { label : 'Samoan', value : 'sm' },
            { label : 'Scots Gaelic', value : 'gd' },
            { label : 'Serbian', value : 'sr' },
            { label : 'Sesotho', value : 'st' },
            { label : 'Shona', value : 'sn' },
            { label : 'Sindhi', value : 'sd' },
            { label : 'Sinhala (Sinhalese)', value : 'si' },
            { label : 'Slovak', value : 'sk' },
            { label : 'Slovenian', value : 'sl' },
            { label : 'Somali', value : 'so' },
            { label : 'Spanish', value : 'es' },
            { label : 'Sundanese', value : 'su' },
            { label : 'Swahili', value : 'sw' },
            { label : 'Swedish', value : 'sv' },
            { label : 'Tagalog (Filipino)', value : 'tl' },
            { label : 'Tajik', value : 'tg' },
            { label : 'Tamil', value : 'ta' },
            { label : 'Telugu', value : 'te' },
            { label : 'Thai', value : 'th' },
            { label : 'Turkish', value : 'tr' },
            { label : 'Ukrainian', value : 'uk' },
            { label : 'Urdu', value : 'ur' },
            { label : 'Uzbek', value : 'uz' },
            { label : 'Vietnamese', value : 'vi' },
            { label : 'Welsh', value : 'cy' },
            { label : 'Xhosa', value : 'xh' },
            { label : 'Yiddish', value : 'yi' },
            { label : 'Yoruba', value : 'yo' },
            { label : 'Zulu', value : 'zu' }
        ],
        library : [
            { name : 'Instagram Hashtag Images', URL : 'https://instagram.com' }
        ],
        categories : [],
        postTypes : [],
        postFormats : [
            { value : '0', label : 'Standard' },
            { value : 'aside', label : 'Aside' },
            { value : 'image', label : 'Image' },
            { value : 'video', label : 'Video' },
            { value : 'quote', label : 'Quote' },
            { value : 'link', label : 'Link' },
            { value : 'gallery', label : 'Gallery' },
            { value : 'status', label : 'Status' },
            { value : 'audio', label : 'Audio' },
            { value : 'chat', label : 'Chat' }
        ],
        latestPosts : [],
        nextPostType : ['post_title', 'post_content', 'featured_image', 'variable', 'variable', 'variable', 'variable', 'variable', 'variable', 'variable', 'variable', 'variable', 'variable'],
        loadFromDisk : false,
        specialNames : {
            post_title : 'Post Title',
            post_content : 'Content',
            featured_image : 'Featured Image',
            gallery : 'Gallery Images',
            _price : 'Price',
            _product_url : 'Product URL',
            tags : 'Tags',
            post_tag : 'Tags',
            product_tag : 'Tags',
            product_cat : 'Categories'
        },
        specialWarnings : {
            _price : 'It should be numeric content!',
            gallery : 'Allowed Extensions : jpg, png, gif or jpeg',
            featured_image : 'Allowed Extensions : jpg, png, gif or jpeg',
            tags : 'Only multiple elements allowed!'
        },
        suggestedField : false,
        suggestions : [],
        saveAsNewProject: false
    },
    methods : {
        init : function(){
            var iframe_feed    = document.getElementById('visual-editor');
            var iframe_content = document.getElementById('content-editor');

            this.iframe['feed']    = iframe_feed;
            this.iframe['content'] = iframe_content;
            
            if( this.form.singlePost === false ){                
                this.form.singlePost = '';                
            }
                        
            window.addEventListener("message", function(event) {
                var data = event.data;

                if(data.key && data.from == 'plugin'){
                    console.log(data.key);
                    if(data.key == 'pushSiteService'){
                        app.pushSiteService(data.value);
                    }
                }
            });

            this.getSiteService();

            setInterval(function(){
                app.applyPaths();
                app.setScaleIframe();
            }, 60);
        },
        disableLoading : function(){
            this.loading = false;
            this.enablePathApply = false;
        },
        scraperLoad : function(type){            
            if(type == 'feed'){
                console.log('Feed URL loaded!');
                if(!app.loadFromDisk){
                    app.loading = false;
                }
                
                app.result.success = true;
                app.queryTime = parseFloat((Date.now() - app.startTime) / 1000).toFixed(2);

                app.Scraper['feed']    = app.iframe['feed'].contentWindow.Scraper;
            }else if(type == 'content'){
                app.loading = false;
                console.log('Content URL loaded!');
                app.Scraper['content'] = app.iframe['content'].contentWindow.Scraper;

                if(app.fetchCallback){
                    app.fetchCallback();
                }
            }
        },
        loadTask : function(hash){
            if(hash){
                app.loading = true;
                app.loadFromDisk = true;

                $.post('service/?request=get_task', { purchase_code : purchase_code, domain : domain, hash : hash }, function(task){

                    if(task.data && task.data.connection){
                        app.connection = task.data.connection;
                    }

                    app.form.hash           = hash;
                    app.form.name           = task.name;
                    app.form.categoryId     = task.category_id;

                    app.advanceMode = true;
                    app.newlyCreatedPost = false;

                    try{
                        if(task.category_ids){
                            app.form.categoryIds = JSON.parse(task.category_ids);
                        }
                    }catch(e){

                    }

                    if(!app.form.categoryIds){
                        app.form.categoryIds = [app.form.categoryId];
                    }
                    
                    app.form.downloadImages = task.download_images == '1' ? true : false;
                    app.form.trackChanges   = task.track_changes == '1' ? true : false;
                    app.form.resetTask      = task.reset_task == '1' ? true : false;
                    app.form.deletePost     = task.delete_post == '1' ? true : false;
                    app.form.deleteMethod   = task.delete_method;
                    app.form.publicTask     = task.public_task == '1' ? true : false;
                    app.connection.ignore_params = app.connection.ignore_params == 'true' ? true : false;

                    app.form.runInterval    = task.run_interval;
                    app.form.runDelay       = task.run_delay;
                    app.form.postStatus     = task.post_status;
                    app.form.postUpdate     = task.post_update;
                    app.form.postType       = task.post_type;
                    app.form.excludeTags    = task.exclude_tags;
                    app.form.excludeField   = task.exclude_field;
                    app.form.taskCondition  = task.task_condition;
                    app.form.parseMethod    = task.parse_method;
                    app.form.filename       = task.filename;
                    app.form.taskLimit      = task.task_limit;

                    if(task.uniqueness_method){
                        app.form.uniquenessMethod = task.uniqueness_method;
                    }

                    app.applyTemplate(JSON.stringify(task.data));
                });

                setTimeout(function(){
                    app.disableLoading();
                }, 20000);
            }
        },
        pushSiteService : function(data){
            console.log(data);
            app.categories    = data.categories;
            app.postTypes     = data.post_types;
            app.temp.accounts = data.accounts;
            app.latestPosts   = data.latest_posts;

            for(var customTypeIndex in data.custom_fields){
                app.customFields.push({ name : data.custom_fields[customTypeIndex] });
            }
        },
        getSiteService : function(){
            if(window.parent.location != window.location){
                window.parent.postMessage({ key : 'get_information', from : 'editor' }, '*');
            }
        },
        fetch : function(loaded){
            if(!this.form.URL.match(/(http|https)\:\/\/(.*?)/)){
                swal('Please enter valid URL!');
                return false;
            }

            app.startTime = Date.now();

            this.loading = true;

            $.get('service/?request=detect&single_post=' + app.form.singlePost + '&URL=' + app.form.URL, function(data){
                app.wizard.result  = data.result;

                if(data.result.length > 3){
                    if(app.form.feed.path || app.form.fields.length > 1){

                    }else{                        
                    }
                }else{
                    app.wizard.enabled = false;
                }
            });

            if(!loaded){
                app.result.contentURL = '';

                if(app.form.singlePost){
                    app.form.contentURL   = this.form.URL + '';
                    app.result.contentURL = app.encodeURL(this.form.URL, '', 'content');
                }else{
                    app.form.feedURL   = this.form.URL + '';
                    app.result.feedURL = app.encodeURL(this.form.URL, '', 'feed');
                }
            }else{
                app.result.feedURL    = app.encodeURL(this.form.feedURL, '', 'feed');
                app.result.contentURL = app.encodeURL(this.form.contentURL, '', 'content');
            }

            setTimeout(function(){
                app.loading = false;
            }, 20000);
        },
        getVariableTags : function(){
            var output = [{ name : 'content', content : '' }, { name : 'source_domain', content : '' }, { name : 'source_url', content : ''}, { name : 'index', content : '1' }, { name : 'hash', content : '75d7082ff8f971ba9769a7c9c5e54f73' }];
            var fields = this.form.fields;

            for(var fieldIndex in fields){
                var field = fields[fieldIndex];

                if(field.type == 'variable'){
                    output.push({ name : field.name, content : this.getSampleContent(field.element, field.prop, field, true) });
                }

                if(field.type == 'image'){
                    output.push({ name : field.name, content : this.getSampleContent(field.element, field.prop, field, true) });
                }
            }

            return output;
        },
        updateSampleContents : function(){
            for(var fieldIndex in this.form.fields){
                var field = this.form.fields[fieldIndex];

                field.isNumber = field.isNumber == 'true' || field.isNumber === true ? true : false;
                field.isMultiple = field.isMultiple == 'true' || field.isMultiple === true ? true : false;
                field.isJSON = field.isJSON == 'true' || field.isJSON === true ? true : false;
                field.cleanNonNumerical = field.cleanNonNumerical == 'true' || field.cleanNonNumerical === true ? true : false;
                field.splitContent      = field.splitContent == 'true' || field.splitContent === true ? true : false;
                field.spinner    = field.spinner == 'true' || field.spinner === true ? true : false;
                field.stripLinks = field.stripLinks == 'true' || field.stripLinks === true ? true : false;
                field.stripTags  = field.stripTags == 'true' || field.stripTags === true ? true : false;
                field.stripAds   = field.stripAds == 'true' || field.stripAds === true ? true : false;
                field.decodeBitly = field.decodeBitly == 'true' || field.decodeBitly === true ? true : false;

                if(!field.filename){
                    
                }

                if(!field.galleryColumns){
                    field.galleryColumns = 3;
                }

                if(typeof field.isRequired == 'undefined'){
                    field.isRequired = false;
                }else{
                    field.isRequired = field.isRequired == 'true' || field.isRequired === true ? true : false;
                }

                if(!field.gallerySize){
                    field.gallerySize = 'medium';
                }

                if(!field.attributeParse){
                    field.attributeParse = 'none';
                }
            }
        },
        parseDomain : function(url){
            var hostname;
            //find & remove protocol (http, ftp, etc.) and get hostname

            if (url.indexOf("//") > -1) {
                hostname = url.split('/')[2];
            }
            else {
                hostname = url.split('/')[0];
            }

            //find & remove port number
            hostname = hostname.split(':')[0];
            //find & remove "?"
            hostname = hostname.split('?')[0];

            return hostname;
        },
        appendGalleryShortCode : function(){
            var applied = false;

            for(var fieldIndex in this.form.fields){
                var field = this.form.fields[fieldIndex];

                if(field.type == 'post_content'){
                    field.content = field.content + ' {{gallery}}';
                    applied = true;
                }
            }

            if(applied){
                swal("Gallery successfully appended.");
            }else{
                swal("There is no post_content field on field list.");
            }
        },
        processTransform : function(input, evulatedContent, field){
            var output = evulatedContent;

            if(evulatedContent){
                output = output.replace(/\{\{content\}\}/g, input);

                //process static tags
                if(this.form.contentURL){
                    output = output.replace(new RegExp('\{\{source_url\}\}', 'gm'), app.form.contentURL );

                    output = output.replace(new RegExp('\{\{source_domain\}\}', 'gm'), this.parseDomain(app.form.contentURL) );
                }

                //Process tags
                var variableTags = this.getVariableTags();

                for(var variableIndex in variableTags){
                    var variable = variableTags[variableIndex];

                    output = output.replace(new RegExp('\{\{' + variable.name + '\}\}', 'gm'), variable.content );
                }

                //Process find and replace
                output = output.replace(new RegExp(field.find, 'gm'), field.replace);

                //Process find and replaces
                for(var replaceIndex in field.replaces){
                    var findAndReplace = field.replaces[replaceIndex];

                    try{
                        output = output.replace(new RegExp(findAndReplace.find, 'gm'), findAndReplace.replace);
                    }catch(e){
                        console.log('replace error!');
                    }
                }

                if(field.isNumber){
                    if(field.cleanNonNumerical){
                        output = output.replace(new RegExp(/[^\d\.]/, 'g'), '');
                    }

                    var value = parseFloat(output);

                    if(field.math){
                        output = eval(field.math + ';');
                    }
                }

                if(field.attributeParse == 'background-image'){
                    if(output.match(/url\((\'|\"|)(.*?)(\'|\"|)\)/)){
                        output = output.match(/url\((\'|\"|)(.*?)(\'|\"|)\)/)[2];
                        output = (output).replace(/\\3a/g, ':').replace(/\\20/g, '').replace(/\\3d/g, '=').replace(/\\26/g, '&').replace(/ /g, '');                        
                    }
                }

                if(output && output.substring && field.clipEnd > 0){
                    output = output.substring(field.clipStart, field.clipEnd);
                }

                if(output && field.clipWordEnd && field.clipWordEnd > 0){
                    var splitedContent = output.split(' ');

                    if(splitedContent.length > 0){
                        output = splitedContent.slice(field.clipWordStart, field.clipWordEnd).join(' ');
                    }
                }

                if(output && field.splitContent == 'true' && field.splitDelimiter && output.split && typeof output != 'undefined'){
                    output = output.split(field.splitDelimiter);
                }

                if(output && output.replace){
                    output = output.replace('https://scraper.site/visual-editor-beta/service/components/proxy.php/', '');
                    output = output.replace('https://scraper.site/visual-editor/service/components/proxy.php/', '');
                }
            }else{
                output = input;
            }

            return output;
        },
        processFilename : function(field){
            var output = field.filename;

                if(field.filename){
                    output = output.replace('\{\{originalname\}\}', 'sample-file');
                    output = output.replace('\{\{hash\}\}', '75d7082ff8f971ba9769a7c9c5e54f73');
                    output = output.replace('\{\{index\}\}', '1');
                    output = output.replace('\{\{random\}\}', Math.floor(Math.random() * 10));
                }

            return output;
        },
        getSampleContent : function(element, prop, field, disableTransform){
            var output = '';

            if(this.form.parseMethod == 'xpath'){
                if(element){
                    var prop = prop ? prop.split(':') : [];

                    if(prop[0] == 'attr' && element.getAttribute){
                        output = element.getAttribute(prop[1]);
                    }else if(prop[0] == 'style'){
                        output = element.style[prop[1]];
                    }else{
                        output = element.innerText;
                    }
                }
            }else{
                var regexIndex = 0;
                
                if(field.regexIndex == -1){
                    regexIndex = 0;
                }else{
                    regexIndex = field.regexIndex;
                }

                output = field.regexResults && field.regexResults.length > 0 ? field.regexResults[regexIndex] : '';
            }

            if(disableTransform){
                return output;
            }else{
                return this.processTransform(output, field ? field.content : '', field);
            }
        },
        getProps : function(element){
            var output = [];

            if(element && element.attributes){
                var attributes = Object.values(element.attributes);

                for(var attrIndex in attributes){
                    var attr = attributes[attrIndex];

                    output.push({ label : ('attr:' + attr.name), sample : element.getAttribute(attr.name) });
                }

                if(element.tagName == 'IMG'){
                    output.push({ label : 'style:backgroundImage', sample : element.style.backgroundImage });
                }
            }

            return output;
        },
        encodeURL : function(URL, method, type){
            return 'service/?request=proxy&URL=' + encodeURIComponent(URL) + '&time=' + Date.now() + '&viewMethod=' + (method ? method : 'HTML') + '&baseURL=' + (app.result.feedURL ? encodeURIComponent(app.form.URL) : '') + '&user_agent=' + this.connection.user_agent + '&cookie=' + this.connection.cookie + '&proxy=' + this.connection.proxy + '&type=' + type;
        },
        updatePostMethod : function(){
            if(this.form.singlePost){
                this.form.frame = 'content';
            }
                        
            
            this.fetch();
        },
        zoomIn : function(){
            this.zoom+=0.1;
            this.Scraper[this.form.frame].updateZoom(this.zoom);
        },
        zoomOut : function(){
            this.zoom-=0.1;
            this.Scraper[this.form.frame].updateZoom(this.zoom);
        },
        removeElement : function(){
            this.Scraper[this.form.frame].selectPath(function(path, siblings, element){
                element.remove();
            }, false, false);
        },
        addPath : function(){
            var type = this.nextPostType[this.form.fields.length];

            this.form.fields.push({
                name    : type + '_' + (this.form.fields.length + 1) + '',
                type    : type,
                filename: '',
                path    : '-',
                prop    : 'innerText',
                element : null,
                display : true,
                selecting : false,
                content : '{{content}}',
                translate : '',
                customContent : false,
                stripTags : false,
                stripLinks : false,
                stripAds : false,
                decodeBitly : false,
                attributeParse : 'none',
                transform : [],
                extract : 'html',
                isRequired : false,
                find : '',
                replace : '',
                replaces : [
                    {
                        find : '',
                        replace : ''
                    }
                ],
                isNumber : false,
                isMultiple : false,
                isJSON : false,
                galleryColumns : '3',
                gallerySize : 'medium',
                cleanNonNumerical : false,
                splitContent : false,
                splitDelimiter : ',',
                math : 'value',
                clipStart : 0,
                clipEnd : 0,
                clipWordStart : 0,
                clipWordEnd : 0,
                spinner : false,
                siblings : [],
                regexIndex : -1,
                regexResults : []
            });
        },
        upPath : function(field){
            var items = field.path.split('/');
            field.path = items.splice(0, items.length - 1).join('/');

            this.applyPath(field, 'content', false);
        },
        detectPath : function(item, method){
            var frame = this.form.singlePost ? 'content' : this.form.frame;

            this.Scraper[frame].detectPath(function(path, siblings, element, link){
                item.path    = path;
                item.element = element;

                console.log(siblings);

                if(siblings){
                    item.siblings = siblings;

                    //feed element, find contentURL
                    if(link && !app.result.contentURL){
                        app.form.contentURL = link;
                        app.result.contentURL = app.encodeURL(link, '', 'content');
                        app.loading = true;
                    }
                }
            }, method);
        },
        selectPath : function(item, serialItem, method){
            var frame = this.form.singlePost ? 'content' : this.form.frame;

            item.selecting = true;

            this.Scraper[frame].selectPath(function(path, siblings, element, link){
                item.path     = path;
                item.element  = element;

                if(siblings){
                    item.siblings = siblings;

                    //feed element, find contentURL
                    if(link && method != 'next_page'){
                        if(app.form.feed.selecting == true){
                            app.form.contentURL = link;
                            app.result.contentURL = app.encodeURL(link, '', 'content');
                            app.loading = true;
                        }
                    }
                }

                if(method == 'featured_image' || method == 'gallery'){
                    if(element.getAttribute('src')){
                        item.prop = 'attr:src';
                    }else if(element.getAttribute('content')){
                        item.prop = 'attr:content';
                    }else if(element.getAttribute('data-src')){
                        item.prop = 'attr:data-src';
                    }
                }
                
                item.selecting = false;
            }, serialItem, method);
        },
        getPathMethod : function(type){
            var output = false;

            if(type == 'gallery'){
                output = 'gallery';
            }else if(type == 'post_content'){
                output = 'article';
            }else if(type == 'tags_input'){
                output = 'tags';
            }else if(type == 'post_title'){
                output = 'title';
            }else if(type == 'variable'){
                output = 'variable';
            }else if(type == 'featured_image'){
                output = 'featured_image';
            }

            return output;
        },
        applyPath : function(item, frame, findSiblings){
            if(this.form.parseMethod == 'regex'){
                var regex = new RegExp(item.path, findSiblings ? 'gm' : 'gm');
                var m;

                var str   = app.iframe[frame].contentWindow.document.body.innerHTML;
                var output = [];

                console.log('Content Length : ', str.length);

                while ((m = regex.exec(str)) !== null) {
                    if (m.index === regex.lastIndex) {
                        regex.lastIndex++;
                    }
                    
                    m.forEach((match, groupIndex) => {
                        if(groupIndex == 1){
                            output.push(match);
                        }
                    });
                }

                if(item.regexIndex == -1){
                    item.regexResults = output;
                }else{
                    item.regexResults = [output[parseInt(item.regexIndex)]];
                }

                console.log('Result count : ', output.length);
            }else if(item && item.path && app.Scraper[frame]){
                app.Scraper[frame].getPath(item.path, function(path, siblings, element, link){
                    item.path    = path;
                    item.element = element;

                    if(siblings){
                        item.siblings = siblings;

                        //feed element, find contentURL
                        if(link && findSiblings){
                            app.form.contentURL = link;
                            app.result.contentURL = app.encodeURL(link, '', 'content');
                            app.loading = true;
                        }
                    }
                    
                    item.selecting = false;
                }, findSiblings);
            }
        },
        regexIndexUp : function(field){
            if(field.regexIndex > -1){
                field.regexIndex--;
            }else{
                swal("Index can not be lower than -1, -1 means all results.");
            }
        },
        regexIndexDown : function(field){
            if(field.regexResults.length > field.regexIndex){
                field.regexIndex++;
            }else{
                swal("Index can not be higher than it\'s limit.");
            }
        },
        refresh : function(){
            app.enablePathApply = true;
            app.applyPaths();
        },
        getSampleCount : function(field){
            var count = 0;

            if(this.form.parseMethod == 'xpath'){
                count = field.siblings ? field.siblings.length : 0;
            }else{
                count = field.regexResults ? field.regexResults.length : 0;
            }

            if(field.splitContent && field.splitDelimiter){
                var sampleContent = this.getSampleContent(field.element, field.prop, field, true);

                if(sampleContent){
                    count = sampleContent.split(field.splitDelimiter).length;
                }
            }

            return count;
        },
        addFindReplaceRule : function(){
            if(!this.selectedField.replaces){
                this.selectedField.replaces = [];
            }

            this.selectedField.replaces.push({
                find : '',
                replace : ''
            });

            app.$forceUpdate();
        },
        deleteFindReplaceRule : function(index){
            this.selectedField.replaces.splice(index, 1);
            app.$forceUpdate();
        },
        updateTypeMethods : function(field){
            if(field.type == 'tags_input'){
                field.name = 'post_tag';
            }

            if(field.type == 'product_tag'){
                field.type = 'tags_input';
                field.name = 'product_tag';

                field.isMultiple = true;
            }

            if(field.type == 'product_cat'){
                field.type = 'tags_input';
                field.name = 'product_cat';

                field.isMultiple = true;
            }

            if(field.type == 'product_attributes'){
                field.type = '_product_attributes';
                field.name = 'pa_colors';

                field.isMultiple = true;
            }

            app.$forceUpdate();
        },
        runScrapingModel : function(next){
            this.form.frame = 'preview';

            if(next){
                this.temp.currentPage++;
                this.saveProject('run_task');
            }else{
                this.temp.currentPage = 0;
                this.saveProject('run_task');
            }
        },
        saveProject : function(method, $narg){
            var output = {};
            
            output.feedURL     = this.form.feedURL;
            output.contentURL  = this.form.contentURL;

            output.fieldsMode  = this.form.fieldsMode;
            output.singlePost  = this.form.singlePost;
            if(this.form.singlePost == 1){
                output.singlePost  = true;
            }            
            output.feed        = this.form.feed;
            output.nextPage    = this.form.nextPage;
            output.fields      = this.form.fields;

            output.other       = this.form.other;

            //Clear siblings
            for(var fieldIndex in output.fields){
                output.fields[fieldIndex].siblings = [];
            }

            output.connection  = this.connection;

            if(output.connection.ignore_params){
                output.connection.ignore_params = output.connection.ignore_params == 'true' ? true : false;
            }

            app.loading = true;
            var ajaxSaveAsNewProject = this.saveAsNewProject;
            var data = JSON.parse(JSON.stringify(output));
            console.log(method)
            $.post('service/?request=' + (method ? method : 'create_task'), {
                hash           : this.form.hash,
                name           : this.form.name,
                categoryId     : this.form.categoryId,
                categoryIds    : this.form.categoryIds,
                downloadImages : this.form.downloadImages,
                trackChanges   : this.form.trackChanges,
                resetTask      : this.form.resetTask,
                deletePost     : this.form.deletePost,
                deleteMethod   : this.form.deleteMethod,
                publicTask     : this.form.publicTask,
                runInterval    : this.form.runInterval,
                runDelay       : this.form.runDelay,
                postStatus     : this.form.postStatus,
                postUpdate     : this.form.postUpdate,
                postType       : this.form.postType,
                filename       : this.form.filename,
                excludeTags    : this.form.excludeTags,
                excludeField   : this.form.excludeField,
                taskCondition  : this.form.taskCondition,
                parseMethod    : this.form.parseMethod,
                uniquenessMethod : this.form.uniquenessMethod,
                taskLimit : this.form.taskLimit,
                nextPage : this.preview.next_page,
                currentPage : this.temp.currentPage,
                domain         : domain,
                purchase_code  : purchase_code,
                data : data,
                getDomain: ajaxSaveAsNewProject ? 'yes' : 'no'
            }, function(data){
                console.log(data);
                app.loading = false;
                ajaxSaveAsNewProject = false;
                if(method){
                    app.preview = data;
                    app.$forceUpdate();
                }else{                    
                    if($narg == 'closeModel'){
                        $('#save-form').modal('hide');
                        swal("Your task successfully created!");
                    } else{ 
                        swal("Your task successfully created!");
                        setTimeout(function(){
                            window.parent.postMessage({ key : 'redirection', from : 'editor' }, '*');
                        }, 1000);
                    }
                }
            });
        },
        removePath : function(item){
            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this field!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    app.form.fields.splice(app.form.fields.indexOf(item), 1);
                }
            });
        },
        switchXMLParse : function(){
            swal({
                title: "XML or RSS Feed parsing?",
                text: "The URL contains RSS or XML structure, do you want to parse with XML method?",
                icon: "warning",
                buttons: true,
                dangerMode: false
            })
            .then((willParse) => {
                if(willParse){
                    var rssURL = 'http://scraper.site/visual-editor/service/?request=rss&url=' + app.form.feedURL;
                    app.form.URL       = rssURL;
                    app.fetch();
                }
            });
        },
        showField : function(content, field_type){
            $('#show-field').modal('show');

            this.fieldPreview = content;
        },
        downloadCSVFunction:function (fields,posts){
            var export_data = '';            
            var myAssociativeArr = [];
            for (var i = 0; i < posts.length; i++) {
                var j = 0;
                var new_arr = new Array();
                for (const field_name of fields) {
                    var d_c = posts[i].post[field_name];
                    if( typeof d_c !== 'undefined' ){
                        var new_s = d_c.toString().replace(/^\s+|\s+$/g,'');
                    }else{
                        var new_s = '';
                    }
                    new_arr[j] = new_s;
                    j++;
                } 
                myAssociativeArr.push(new_arr);
            }            
            this.exportToCsv('export.csv', [
                fields,	
                myAssociativeArr
            ]);
        },
        exportToCsv: function(filename, rows){
            var processRow = function (row) {
                var finalVal = '';
                for (var j = 0; j < row.length; j++) {
                    var innerValue = row[j] === null ? '' : row[j].toString();
                    if (row[j] instanceof Date) {
                        innerValue = row[j].toLocaleString();
                    };
                    var result = innerValue.replace(/"/g, '""');
                    if (result.search(/("|,|\n)/g) >= 0)
                        result = '"' + result + '"';
                    if (j > 0)
                        finalVal += ',';
                    finalVal += result;
                }
                return finalVal + '\n';
            };

            var csvFile = '';
            for (var i = 0; i < rows.length; i++) {
                    if(i == 1){
                    rows[1].forEach(function(item,index){
                        csvFile += processRow(item);
                    });
                }else{
                    csvFile += processRow(rows[i]);
                }
            }

            var blob = new Blob([csvFile], { type: 'text/csv;charset=utf-8;' });
            if (navigator.msSaveBlob) { // IE 10+
                navigator.msSaveBlob(blob, filename);
            } else {
                var link = document.createElement("a");
                if (link.download !== undefined) { // feature detection
                    // Browsers that support HTML5 download attribute
                    var url = URL.createObjectURL(blob);
                    link.setAttribute("href", url);
                    link.setAttribute("download", filename);
                    link.style.visibility = 'hidden';
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                }
            }
        },
        stringClip : function(string, limit){
            if(string.substring){
                return string.substring(0, limit);
            }else{
                return string;
            }
        },
        changeParseMethod : function(method){
            this.form.parseMethod = method;
        },
        showLibrary : function(){
            app.loading = true;

            $.get('service/?request=library', function(data){
                app.loading = false;
                app.library = data;
                $('#library').modal('show');
            });
        },
        saveConnectionSettings : function(){            
            $('#connection-settings').modal('hide');
        },
        applyTemplate : function($site){
            var site = JSON.parse($site);
            
            this.form.singlePost  = site.singlePost == 'true' || site.singlePost === true || site.singlePost == 1 ? 1 : '';
            this.form.URL         = site.feedURL ? site.feedURL : site.contentURL;
            this.form.feedURL     = site.feedURL;
            this.form.contentURL  = site.contentURL;
            this.form.fieldsMode  = site.fieldsMode ? site.fieldsMode : 'custom-post';

            app.advanceMode = true;
            app.newlyCreatedPost = false;

            this.form.feed     = site.feed;
            this.form.nextPage = site.nextPage;
            this.form.fields   = site.fields;

            if(site.other){
                this.form.other = site.other;
            }else{
                this.form.other = {
                    noStatusChange : false,
                    postFormat : '0',
                    bulkURL : ''
                };
            }

            if(this.form.singlePost){
                this.form.frame = 'content';
            }

            if(!this.form.feed.siblings){
                this.form.feed.siblings = [];
            }

            this.fetch(true);
            this.fetchCallback = function(){
                $('#library').modal('hide');
                app.enablePathApply = true;
            }
        },
        setScaleIframe : function(){
            var scaleX = $('.responsive-frame').width() / 1280;
            var heightValue = $('.responsive-frame').height() / scaleX;

            $('.responsive-frame iframe').css('transform', 'scale(' + scaleX + ')');
            $('.responsive-frame iframe').css('height', heightValue);
        },
        applyPaths : function(){
            if(app.enablePathApply && app.Scraper['content']){
                app.loading = true;

                //wait for render
                if(app.Scraper['feed']){
                    app.applyPath(app.form.feed, 'feed');
                    app.applyPath(app.form.nextPage, 'next-page');
                }

                for(var fieldIndex in app.form.fields){
                    var field = app.form.fields[fieldIndex];

                    app.applyPath(field, 'content', false);
                }

                if(app.form.feed.element || app.form.singlePost){
                    app.updateSampleContents();
                    app.loading = false;
                    app.enablePathApply = false;
                }else{
                    console.log('apply pathes!');
                }
            }
        },
        redirectPath : function(){
            if(app.form.feed.siblings && app.form.feed.siblings[app.redirectionSamples] && app.form.feed.siblings[app.redirectionSamples].getAttribute('original-href')){
                var link = app.form.feed.siblings[app.redirectionSamples].getAttribute('original-href');

                app.form.contentURL = link;
                app.result.contentURL = app.encodeURL(link, '', 'content');
                app.loading = true;
            }
        },
        showTransform : function(item){
            this.selectedField = item;

            //Clean boolean fields

            this.updateSampleContents();

            $('#transform-form').modal('show');
        },
        saveTransform : function(){
            this.selectedField = {};

            $('#transform-form').modal('hide');
        },
        openSaveModal : function(){
            this.getSiteService();
            this.saveAsNewProject = false;
            $('#save-form').modal('show');
        },
        openSaveAsModal : function(){
            this.getSiteService();            
            this.saveAsNewProject = true;
            $('#save-form').modal('show');
        },
        showResultsModal : function(){

        },
        showSettings : function(){
            $('#connection-settings').modal('show');
        },
        showBulkURLs : function(){
            $('#bulk-url-list').modal('show');
        },
        switchView : function(view){
            app.loading = true;
            app.viewMethod = view;
            app.result.feedURL    = app.encodeURL(app.form.feedURL, app.viewMethod, 'feed');
            app.result.contentURL = app.encodeURL(app.form.contentURL, app.viewMethod, 'content');
        },
        limitString : function(string){
            if(string){
                return string.trim().substring(0, 15);
            }else{
                return '';
            }
        },
        checkCategoryField : function(){
            var output = false;

            for(var index in this.form.fields){
                var field = this.form.fields[index];

                if(field.type == 'post_category'){
                    output = true;
                }
            }

            return output;
        },
        addNewTemplateField : function(){
            this.addPath();
            var lastItem = this.form.fields[this.form.fields.length - 1];
            
            return lastItem;
        },
        warningCheckField : function(field){
            var output = false;

            if(field){
                var content = this.getSampleContent(field.element, field.prop, field);
                var count   = this.getSampleCount(field);

                if(content && field.type == '_price'){
                    if(Number.isNaN(content)){
                        output = true;
                    }
                }

                if(field.type == 'tags' && field.isMultiple){
                    if(count < 1){
                        output = true;
                    }
                }

                if(content && field.type == 'featured_image' || field.type == 'gallery'){
                    if(content.search && content.search(/(jpg|png|gif|jpeg)/g) > -1){

                    }else{
                        output = true;
                    }
                }
            }

            return output;
        },
        runCommand : function(){
            console.log('Command run!');
            this.Scraper[this.form.frame].runCommand(this.temp.command);
            this.temp.command = '';
        },
        addProductAttribute : function(attributeName){
            var lastItem = this.addNewTemplateField();
                lastItem.isMultiple = true;
                lastItem.type       = '_product_attributes';
                lastItem.name       = 'pa_' + attributeName;
        },
        createTemplate : function(type){
            app.form.fieldsMode = type;

            if(type == 'simple-post'){
                //post content
                var lastItem = this.addNewTemplateField();
                lastItem.type       = 'post_content';
                lastItem.content    = '{{content}} {{gallery}}';
                lastItem.stripTags  = true;
                lastItem.stripLinks = true;
                lastItem.stripAds   = true;

                //featured image
                var lastItem = this.addNewTemplateField();
                lastItem.type       = 'featured_image';
                lastItem.prop       = 'attr:src';

                //gallery
                var lastItem = this.addNewTemplateField();
                lastItem.isMultiple = true;
                lastItem.type       = 'gallery';
                lastItem.prop       = 'attr:src';

                //tags
                var lastItem = this.addNewTemplateField();
                lastItem.isMultiple = true;
                lastItem.type       = 'tags_input';
                lastItem.name       = 'post_tag';

                this.newlyCreatedPost = false;
            }else if(type == 'simple-product'){
                //post content
                var lastItem = this.addNewTemplateField();
                lastItem.type       = 'post_content';
                lastItem.stripTags  = true;
                lastItem.stripLinks = true;
                lastItem.stripAds   = true;

                //featured image
                var lastItem = this.addNewTemplateField();
                lastItem.type       = 'featured_image';
                lastItem.prop       = 'attr:src';

                //gallery
                var lastItem = this.addNewTemplateField();
                lastItem.isMultiple = true;
                lastItem.type       = 'gallery';
                lastItem.prop       = 'attr:src';

                //price
                var lastItem = this.addNewTemplateField();
                lastItem.type       = '_price';
                lastItem.isNumber   = true;

                //product tags
                var lastItem = this.addNewTemplateField();
                lastItem.isMultiple = true;
                lastItem.type       = 'tags_input';
                lastItem.name       = 'product_tag';

                //product category
                var lastItem = this.addNewTemplateField();
                lastItem.isMultiple = true;
                lastItem.type       = 'tags_input';
                lastItem.name       = 'product_cat';

                var lastItem = this.addNewTemplateField();
                lastItem.type       = '_product_url';
                lastItem.prop       = 'attr:href';

                app.form.postType = 'product';

                this.newlyCreatedPost = false;
            }else{
                this.newlyCreatedPost = false;
                this.advanceMode = true;
            }
        },
        clearString : function(string){
            return string.replace(/(?:(?:^|\n)\s+|\s+(?:$|\n))/g,'').replace(/\s+/g,' ').trim().toLowerCase();
        },
        getSuggestions : function(field){
            this.suggestedField = field;

            app.Scraper['content'].getElementsBySelector('*', function(element, xpath){
                var value = element.text();

                if(value){
                    var sample = app.clearString(value);

                    app.suggestions.push({
                        sample : sample,
                        element : element
                    });
                }
            });
        },
        similar : function(a,b) {
            var lengthA = a.length;
            var lengthB = b.length;
            var equivalency = 0;
            var minLength = (a.length > b.length) ? b.length : a.length;    
            var maxLength = (a.length < b.length) ? b.length : a.length;    
            for(var i = 0; i < minLength; i++) {
                if(a[i] == b[i]) {
                    equivalency++;
                }
            }


            var weight = equivalency / maxLength;
            return (weight * 100);
        },
        filterSearch : function(suggestions, field){
            var output = [];

            if(!field.path && field.path == '-'){
                return output;
            }

            for(var index in suggestions){
                var suggestion = suggestions[index];

                var path   = app.clearString(field.path);
                var sample = app.clearString(suggestion.sample).substring(0, path.length);

                output.push({
                    score : this.similar(sample, path),
                    suggestion : suggestion
                });
            }

            output = output.sort(function(a, b){
                return b.score - a.score;
            });

            output = output.slice(0, 30);

            return output;
        },
        selectElement : function(item, field){
            field.path = app.Scraper['content'].getPathTo(item.suggestion.element[0]);
            console.log(field.path);
            this.suggestedField = false;
        },
        generatePaginationList : function(){
            var sampleURL = this.temp.paginationURL;
            var output = [];

            for(var i = this.temp.startNumber; i <= this.temp.endNumber; i++){
                output.push(sampleURL.replace('{{number}}', i));
            }

            this.form.other.bulkURL = output.join('\n');
            this.bulkTab = 'url-list';
        },
        checkPostType : function(post, type){
            if(post && post.post_type){
                return post.post_type == type;
            }else{
                return true;
            }
        }
    },
    computed : {
        filterLibrary : function(){
            var output = [];

            for(var itemIndex in this.library){
                var item = this.library[itemIndex];

                if(this.form.search.length > 0){
                    if(
                        item.name.toLowerCase().search(this.form.search.toLowerCase()) > -1 ||
                        item.URL.toLowerCase().search(this.form.search.toLowerCase()) > -1
                    ){
                        output.push(item);
                    }
                }else{
                    output.push(item);
                }
            }

            return output;
        }
    }
});

app.init();