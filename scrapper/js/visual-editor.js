'use strict';
var xpathSelector = {
	disallow : ['tbody', 'html', 'body'],
	disallow_2 : ['html', 'body'],
	articleTags : ['p', 'div', 'span', 'li'],
	getXpath : function(element, selectMethod){
		var parentCount  = 0;
		var output       = [];
		var reversed     = $(element).parents().addBack().get().reverse();
		var extended     = false;
		var parentUsed   = false;
		var idElement    = '';

		$(reversed).each(function () {
			var element = $(this);
			var elementTag  = element.prop('tagName').toLowerCase();
			var elementPath = elementTag;

			//finish list
			if( elementTag == 'body' || elementTag == 'html' ){
				return false;
			}

			if( element.attr('id') ){
				var elementId = element.attr('id');
					elementId = xpathSelector.cleanDigits(elementId);

				if(elementId != ''){
					idElement = elementPath + '[@id="' + elementId + '"]';
					output.push(idElement);
					return false;
				}
			}

			if( element.attr('class') ){
				var elementClass = element.attr('class');
					elementClass = xpathSelector.cleanDigits(elementClass);

				if(elementClass != ''){
					//clean classname
					elementClass = xpathSelector.cleanName(elementClass);
					var elementXpath  = '[contains(concat (" ", normalize-space(@class), " "), "' + elementClass + '")]';

					output.push(elementPath + elementXpath);

					//check if element xpath provides single item
					var tempPath = elementPath + elementXpath;

					if(xpathSelector.queryPath('//' + tempPath).length == 1 && parentCount == 0){
						output = [];
						output.push(tempPath);
					}
					return false;
				}
			}

			if ( element.siblings(elementPath).length > 0 ) {
				var index = element.prevAll(elementTag).length + 1;

				//check article state
				if(
					selectMethod == 'article' && 
					xpathSelector.articleTags.indexOf(elementTag) > -1 &&
					!parentUsed
				){
					elementTag = "p";
					parentUsed = true;
				}else if(
					(selectMethod == 'gallery' || selectMethod == 'tags' || selectMethod == 'href') &&
					extended == false
				){
					if(parentCount > 0){
						elementTag += '/';
					}else{
						elementTag = '/' + elementTag;
					}
				}else{
					elementTag += "[" + index + "]";
				}
			}else{
				if(
					selectMethod == 'article' && 
					xpathSelector.articleTags.indexOf(elementTag) > -1 &&
					!parentUsed
				){
					elementTag = "p";
					parentUsed = true;
				}
			}

			parentCount++;
			output.push(elementTag);
		});

		var joinedPath = xpathSelector.cleanPath('//' + output.reverse().join('/'));

		if(selectMethod == 'featured_image') {
			////meta[contains(@property, "image")]

			if(element && $(element).find('img').length > 0){
				return joinedPath + '//img' + ' | ' + xpathSelector.getAbsolutePath(element) + '//img';
			}else{
				return joinedPath + ' | ' + xpathSelector.getAbsolutePath(element);
			}
		}else if(selectMethod == 'title') {
			return joinedPath + ' | ' + xpathSelector.getAbsolutePath(element) + ' | //h1';
		}else if(selectMethod == 'href') {
        	var linkElement = xpathSelector.addAncestor(joinedPath, 'a');
        	return linkElement + ' | ' + xpathSelector.addAncestor(xpathSelector.getAbsolutePath(element), 'a');
        }else if(selectMethod == 'next_page') {
        	//special condition
        	var elementTag   = element.tagName.toLowerCase();
        	var innerContent = xpathSelector.cleanName(element.innerText);
        	var nextPagePath = '//' + elementTag + '[contains(concat (" ", normalize-space(text()), " "), "' + innerContent + '")]';
        	return '//link[@rel="next"]' + ' | ' + nextPagePath + ' | ' + joinedPath + ' | ' + xpathSelector.getAbsolutePath(element);
        }else if(selectMethod == 'article') {
        	var extraElement = '';

        	if(idElement){
        		extraElement+= ' | //' + idElement + '/parent::*[(self::p or self::div or self::span or self::li)]';
        	}
        	return joinedPath + ' | ' + xpathSelector.getAbsolutePath(element) + extraElement;
        }else if(selectMethod == 'variable') {
        	return xpathSelector.getAbsolutePath(element);
        }else if(parentCount == 0) {
            return joinedPath;
        }else {
        	//or expression
            return joinedPath + ' | ' + xpathSelector.getAbsolutePath(element);
		}
	},
	addAncestor : function(path, element){
		if(path.split('/').pop().search(element) > -1){
			//check for element fix
			var match = path.match(/(.*?)\/a(\[(.*?)\]|)\/(.*)/);

			console.log(match);
			if(match && match.length > 0 && match[4]){
				return match[1] + '/a';
			}else{
				return path;
			}
		}else{
			return path + '/ancestor::' + element;
		}
	},
	getAbsolutePath : function(element){
		var output   = [];
		var reversed = $(element).parents().addBack().get().reverse();

		$(reversed).each(function () {
			var elementTag  = $(this).prop('tagName').toLowerCase();
			var elementPath = elementTag;

			if ( $(this).siblings(elementTag).length > 0 ) {
				var index = $(this).prevAll(elementTag).length + 1;
				elementPath+='[' + index + ']';
			}

			if(xpathSelector.disallow_2.indexOf(elementTag) == -1){
				output.push(elementPath);
			}
		});

		var path = this.cleanPath(output.reverse().join('/'));
			path = path.replace(/tbody(\[(.*?)\]|)/g, '');

		return '//' + path;
	},
	queryPath : function(path){
		if(path == '-' || path == ''){
			return null;
		}else{
			var item;
			var result = [];
			var xpaths = document.evaluate(path, document, null, XPathResult.ORDERED_NODE_ITERATOR_TYPE, null);

			while (item = xpaths.iterateNext()) {
				result.push(item);
			}

			return $([]).pushStack(result);
		}
	},
	cleanName : function(value){
		var value = value.trim().replace(/\s+/g, ' ');
			value = value.split(' ');

		return value[0];
	},
	cleanDigits : function(value){
		return value.split(/\s+/).filter(function(c){
			return ! /\d/.test(c);
		}).join(' ');
	},
	cleanPath : function(path){
		var path = path;
			path = path.replace(/\/\/\/\//g, '\/\/');
			path = path.replace(/\/\/\//g, '\/\/');

		return path;
	}
};

var Scraper = {
	zoom : 1,
	callbackPath : null,
	selectedPath : null,
	hoverElement : null,
	highlightElement : false,
	serialItem : false,
	selectingMethod : false,
	loaded : false,
	detectAlternatives : [],
	detectAlternativeIndex : 0,
	waitForLoad : function(){
		setInterval(function(){
			Scraper.init();
		}, 200);
	},
	init : function(){
		if(Scraper.loaded){
			return false;
		}

		if(document.body){
			if(typeof console == 'undefined'){
				console = {
					log : function(){

					}
				};
			}
			
			console.log('Hello from scraper');
			console.log(document.body.innerText.length);

			if(document.body.innerText.length < 200){
				//window.parent.swal('Plugin unable to connect website or website doesn\'t provide any content.');
			}                        
                        
			if(
				document.body.innerHTML.search('<rss') == 0 ||
				document.body.innerHTML.search('<feed') == 0 ||
				document.body.innerHTML.indexOf('xmlns:content') > -1 ||
				document.body.innerHTML.indexOf('xmlns=') > -1
			){                                
				window.parent.app.switchXMLParse();
			}

			Scraper.bindings();

			Scraper.loaded = true;

			if(window.location.href.search('request%3Drss') > -1){
				$('a').each(function(){
					if($(this).text().search('http') > -1){
						
				    }else{
						//$(this).hide();
				    }
				});
			}

			setTimeout(function(self){
				window.parent.app.scraperLoad(self.getParameterByName('type'));
			}, 100, this);
		}
	},
	getElementsBySelector : function(selector, callback){
		$(selector).each(function(){
			callback($(this));
		});
	},
	getParameterByName : function(name, url) {
	    if (!url) url = window.location.href;
	    name = name.replace(/[\[\]]/g, '\\$&');
	    var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
	        results = regex.exec(url);
	    if (!results) return null;
	    if (!results[2]) return '';
	    return decodeURIComponent(results[2].replace(/\+/g, ' '));
	},
	bindings : function(){
		console.log('waiting...');
		if(!this.highlightElement){
			this.highlightElement = document.createElement('div');
			this.highlightElement.className = 'highlight-element';
			this.highlightElement.style.display = 'none';
			this.highlightElement.highlight = true;

			document.body.appendChild(this.highlightElement);

			document.addEventListener('click', function(e) {
			    e = e || window.event;
			    var target = e.target || e.srcElement;
			    Scraper.select(target, Scraper.serialItem);
			}, false);

			document.addEventListener('mouseover', function(e) {
			    e = e || window.event;
			    var target = e.target || e.srcElement;

			    if(!target.highlight){
			    	Scraper.hoverElement = target;
			    }
			}, false);

			document.addEventListener('mouseleave', function(e) {
			    e = e || window.event;
			    var target = e.target || e.srcElement;

			    Scraper.blur();
			}, false);

			setInterval(function(){
				if(Scraper.hoverElement){
					Scraper.highlight(Scraper.hoverElement);
				}
			}, 10);
		}
	},
	highlight : function(element){
		if(this.callbackPath){
			var boundBox = element.getBoundingClientRect();

			this.highlightElement.style.left = boundBox.left + 'px';
			this.highlightElement.style.top  = boundBox.top + 'px';

			this.highlightElement.style.width  = boundBox.width + 'px';
			this.highlightElement.style.height = boundBox.height + 'px';

			this.highlightElement.style.display = 'block';
		}else{
			this.blur();
		}
	},
	blur : function(){
		this.highlightElement.style.display = 'none';
	},
	getContent : function(element){
		if(element.tagName == 'IMG'){
			return element.src;
		}else{
			return element.innerHTML;
		}
	},
	getLink : function(element, attempt){
		if(element && element.getAttribute && element.getAttribute('original-href')){
			return element.getAttribute('original-href');
		}else if(element && element.parentNode && element.parentNode.getAttribute && element.parentNode.getAttribute('original-href')){
			return element.parentNode.getAttribute('original-href');
		}else if(element && element.childNodes && element.childNodes.attributes > 0 && element.childNodes[0].getAttribute('original-href')){
			return element.childNodes[0].getAttribute('original-href');
		}else if(element && element.parentNode && attempt < 7){
			return this.getLink(element.parentNode, attempt ? attempt + 1 : 0);
		}
	},
	getAElement : function(element){
		if(element && element.tagName == 'A'){
			return element;
		}else if(element && element.parentNode){
			return this.getAElement(element.parentNode);
		}else{
			return false;
		}
	},
	getElements : function(xpathToExecute, catchErrors){
		var result = [];

		try{
			var nodesSnapshot = document.evaluate(xpathToExecute, document, null, XPathResult.ORDERED_NODE_SNAPSHOT_TYPE, null );
			for ( var i=0 ; i < nodesSnapshot.snapshotLength; i++ ){
				result.push( nodesSnapshot.snapshotItem(i) );
			}
		}catch(e){
			if(catchErrors){
				//window.parent.swal('Xpath couldn\'t get siblings of this element, please use custom xpath to find pattern.');
			}
		}
		
		return result;
	},
	getElementList : function(xpath){
		if(xpath && xpath.length > 4){
			var realPath  = xpath.split('/');
				realPath  = realPath.splice(0, realPath.length - 1).join('/');

				if(realPath.substr(-3).search(/\[/) > -1){
					realPath  = realPath.slice(0, -3);
				}
				
			var elements = this.getElements(realPath);

			return { elements : elements, path : realPath };
		}else{
			return false;
		}
	},
	getElementByXpath : function(path) {
		if(path == '-' || path == ''){
			return null;
		}else{
			try{
				return document.evaluate(path, document, null, XPathResult.FIRST_ORDERED_NODE_TYPE, null).singleNodeValue;
			}catch(e){
				return null;
			}
		}
	},
	getSiblings : function(path){
		var siblings = [];

		var i = path.split('/').length;
		var newPath = path;

		while(i--){
			sibling  = this.getElementList(newPath);

			if(sibling){
				siblings.push({ elements : sibling.elements, path : sibling.path, count : sibling.elements.length, score : i });
				newPath = sibling.path;
			}
		}

		siblings.sort(function(a, b){
			return (b.count) - (a.count);
		});

		if(siblings[0]){
			//convert path to absolute path
			var splitPath = path.split(siblings[0].path);
				splitPath = splitPath[1].substring(3);

			var path = siblings[0].path + '/' + splitPath;

			if(Scraper.serialItem){
				//find A tag on xpath
				var newPath = path.replace( /\/a(\[(.*?)\]|)\/(.*)/gm, "/a$1");
					newPath = newPath.replace( /\/\/(.*?)\[(.*?)\]/gm, "//$1");
			}

			var elements = this.getElementList(newPath);
			return [elements.elements, newPath];
		}else{
			return [];
		}
	},
	getClassPath : function(element, child, attempt, prefix){
		if(element && element.className){
			//to make it more accure, check if it's parent node has classname
			if(element.parentNode && element.parentNode.className){
				var firstClassName = element.parentNode.className.replace(/(\d*)/g, '');
					firstClassName = firstClassName.split(' ');
					firstClassName = firstClassName.filter(function(e){return e})[0];

				var secondClassName = element.className.replace(/(\d*)/g, '');
					secondClassName = secondClassName.split(' ');
					secondClassName = secondClassName.filter(function(e){return e})[0];

					if(element.tagName == 'A'){
						return '//' + element.parentNode.tagName.toLowerCase() + '[contains(@class, "' + firstClassName + '")]/' + element.tagName.toLowerCase() + '[contains(@class, "' + secondClassName + '")]';
					}else{
						return '//' + element.parentNode.tagName.toLowerCase() + '[contains(@class, "' + firstClassName + '")]/' + element.tagName.toLowerCase() + '[contains(@class, "' + secondClassName + '")]//a';
					}
			}else if(child){
				var firstClassName = element.className.replace(/(\d*)/g, '');
					firstClassName = firstClassName.split(' ');
					firstClassName = firstClassName.filter(function(e){return e})[0];

				return '//' + element.tagName.toLowerCase() + '[contains(@class, "' + firstClassName + '")]//' + child.tagName.toLowerCase() + (prefix ? prefix : '');
			}else{
				var firstClassName = element.className.replace(/(\d*)/g, '');
					firstClassName = firstClassName.split(' ');
					firstClassName = firstClassName.filter(function(e){return e})[0];

				return '//' + element.tagName.toLowerCase() + '[contains(@class, "' + firstClassName + '")]';
			}
		}else if(element && element.parentNode){
			if(attempt){
				attempt++;
			}else{
				var attempt = 0;
			}

			return this.getClassPath(element.parentNode, child ? child : element, attempt);
		}
	},
	select : function(element){
		var siblings = [];
		var xpath = false;			

		console.log(element.className);
		if(element.className.search('meta_tag') > -1){
			var xpath = element.getAttribute('path');
			var element = this.getElementByXpath(xpath);

			this.selectedPath = xpath;
		}else{
			this.selectedPath = xpathSelector.getXpath(element, this.selectingMethod);
		}

		var siblings      = this.getElements(this.selectedPath, true);

		this.callbackPath(
			this.selectedPath, 
			siblings, 
			this.getElementByXpath(this.selectedPath), 
			this.getLink(element)
		);

		this.callbackPath = false;
	},
	selectPath : function(callback, serialItem, method){
		this.callbackPath = callback;

		if(serialItem){
			this.serialItem = serialItem;
		}else{
			this.serialItem = false;
		}

		console.log(method);

		if(method){
			this.selectingMethod = method;
		}else{
			this.selectingMethod = false;
		}
	},
	getPath : function(xpath, callback){
		var element = this.getElementByXpath(xpath);
		this.selectedPath = xpath;

		//var $siblings = this.getElementList(this.selectedPath);
		var $siblings = this.getElements(this.selectedPath);

		callback(this.selectedPath, $siblings, this.getElementByXpath(this.selectedPath), this.getLink(element));
	},
	updateZoom : function(zoom){
		this.zoom = zoom;
		document.body.style.zoom = zoom;
	},
	runCommand : function(command){
		eval(command);
	},
	findSameClasses : function(element, className){
		var indexFactor = '';
		var ix = 0;
	    var siblings= element.parentNode.childNodes;
	    for (var i= 0; i<siblings.length; i++) {
	        var sibling = siblings[i];
	        if (sibling===element){
	            indexFactor = '['+(ix+1)+']';
	        }

	        if (sibling.nodeType===1 && sibling.tagName===element.tagName && sibling.className && sibling.className.search(className) > -1){
	        	console.log(element, element.className, className, ix);
	            ix++;
	        }
	    }

	    return indexFactor;
	},
	getWithClassName : function(element){
		if(element && element.tagName && element.className && element.tagName != 'BODY' && element.tagName != 'HTML'){
			var tagname = element.tagName.toLowerCase();
			var _classname = element.className.replace(/(\d*)/g, '');
			var clearClassname = _classname.split(' ');

			if(clearClassname[0] && clearClassname[0].search(/(\d)/) == -1){
				var classname = clearClassname[0];
				return '/' + tagname + '[contains(@class, "' + classname + '")]' + Scraper.findSameClasses(element, classname);
			}else if(clearClassname[1] && clearClassname[1].search(/(\d)/) == -1){
				var classname = clearClassname[1];
				return '/' + tagname + '[contains(@class, "' + classname + '")]' + Scraper.findSameClasses(element, classname);
			}else if(clearClassname[2] && clearClassname[2].search(/(\d)/) == -1){
				var classname = clearClassname[2];
				return '/' + tagname + '[contains(@class, "' + classname + '")]' + Scraper.findSameClasses(element, classname);
			}else if(clearClassname[3] && clearClassname[3].search(/(\d)/) == -1){
				var classname = clearClassname[3];
				return '/' + tagname + '[contains(@class, "' + classname + '")]' + Scraper.findSameClasses(element, classname);
			}else{
				return '/' + tagname;
			}
		}else if(element && element.tagName && element.tagName != 'BODY' && element.tagName != 'HTML'){
			var tagname = element.tagName.toLowerCase();
			return '/' + tagname;
		}else{
			return '';
		}
	},
	anotherXpathMethod : function(element){
		var output = '';

		if(element){
			output = Scraper.getWithClassName(element);
		}

		if(element.parentNode){
			output = Scraper.getWithClassName(element.parentNode) + output;

			if(element.parentNode.parentNode){
				output = Scraper.getWithClassName(element.parentNode.parentNode) + output;

				if(element.parentNode.parentNode.parentNode){
					output = Scraper.getWithClassName(element.parentNode.parentNode.parentNode) + output;

					if(element.parentNode.parentNode.parentNode.parentNode){
						output = Scraper.getWithClassName(element.parentNode.parentNode.parentNode.parentNode) + output;

						if(element.parentNode.parentNode.parentNode.parentNode.parentNode){
							output = Scraper.getWithClassName(element.parentNode.parentNode.parentNode.parentNode.parentNode) + output;
						}
					}
				}
			}
		}

		output = output.replace(/tbody(\[(.*?)\]|)/g, '');
		output = output + ' | /' + Scraper.getWithClassName(element);

		return output.slice(1, output.length);
	},
	getPathTo : function(element) {
		var path = Scraper.anotherXpathMethod(element);

    	if(path && path.search('/(td|tr)') === -1 && path.search(/contains/g) > -1){
    		return path;
    	}else{
		    if (element.id!=='' && element.id && !element.id.match(/(\d)/))
		        return '*[@id="'+element.id+'"]';
		    if (element===document.body)
		        return element.tagName.toLowerCase();

		    var ix= 0;
		    var siblings= element.parentNode.childNodes;
		    for (var i= 0; i<siblings.length; i++) {
		        var sibling= siblings[i];
		        if (sibling===element)
		            return Scraper.getPathTo(element.parentNode)+'/'+element.tagName.toLowerCase()+'['+(ix+1)+']';
		        if (sibling.nodeType===1 && sibling.tagName===element.tagName)
		            ix++;
		    }
		}
	},

	//Detect modules
	detectPath : function(callback, method){
		this.callbackPath = callback;

		if(method){
			this.selectingMethod = method;
		}else{
			this.selectingMethod = false;
		}

		this.detectAlternatives = this.getSerialLinks();

		if(this.detectAlternatives.length > 0){
			this.selectingMethod = 'href';
			this.serialItem = true;
			this.updateAlternativeIndex();
		}
	},
	updateAlternativeIndex : function(){
		if(this.detectAlternatives[this.detectAlternativeIndex]){
			this.select(this.detectAlternatives[this.detectAlternativeIndex].hash.element[0]);
		}
	},
	nextAlternative : function(){
		if(this.detectAlternatives.length > this.detectAlternativeIndex){
			this.detectAlternativeIndex++;
		}

		this.updateAlternativeIndex();
	},
	prevAlternative : function(){
		if(this.detectAlternativeIndex > 1){
			this.detectAlternativeIndex--;
		}

		this.updateAlternativeIndex();
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
	groupByHashes : function(hashes){
		var output = [];
		var classScore = {};

		for(var hashIndex0 in hashes){
			var hash0 = hashes[hashIndex0];

			for(var hashIndex1 in hashes){
				var hash1 = hashes[hashIndex1];

				if(hash0.string != hash1.string && 
					this.similar(hash0.string, hash1.string) > 30
				){
					if(!output[hash0.string]){
						output[hash0.string] = {
							count : 0,
							hash  : hash0
						};
					}

					if(!classScore[hash0.classNames]){
						classScore[hash0.classNames] = 0;
					}

					var score = classScore[hash0.classNames] + hash0.content.length * 10;

					//check title classes
					if(hash0.classNames.search(/(bookmark|entry|post|title|name)/g) > -1){
						score+=200;
					}

					if(hash0.classNames.search(/(menu|footer|page|tag|author|genre|category|read|more|lang|options|user)/g) > -1){
						score-=200;
					}

					if(hash0.content.search(/(read|more)/g) > -1){
						score-=200;
					}

					//does it repeats?
					for(var hashIndex2 in hashes){
						var hash2 = hashes[hashIndex2];

						if(this.similar(hash0.content, hash2.content) > 90){
							//score-=1;
						}
					}

					if(hash0.parentNode.search(/(h1|h2|h3|h4|h5|h6)/g) > -1){
						score+=100;
					}

					classScore[hash0.classNames]+=score * 0.001;

					output[hash0.string]['count']+=score;
				}
			}
		}

		output = Object.values(output).sort(function(a, b){
			return b['count'] - a['count'];
		});

		return output;
	},
	getSerialLinks : function(){
		var items = [];
		$('div, li, h1, h2, h3, h4, h5, p').find('a').each(function(){
			var item = $(this);

			if(item.text().length > 2){
				items.push(item);
			}
		});

		//group items with href similarity
		var hashes = [];
		for(var itemIndex in items){
			var item = items[itemIndex];
			var classNames = '';
			var content = '';

			if(item.attr('class')){
				classNames+=' ' + item.attr('class');
			}

			if(item.attr('rel')){
				classNames+=' ' + item.attr('rel');
			}

			if(item.parent().attr('class')){
				classNames+=' ' + item.parent().attr('class');
			}

			if(item.find('*').first().attr('class')){
				classNames+=' ' + item.find('*').first().attr('class');
			}

			if(item.text()){
				content = item.text();
			}

			var valid = 0;
			var href  = item.attr('original-href');

			try{
				if(item.attr('class')){
					if($('.' + item.attr('class').replace(/\n/g, '').trim()).length > 3){
						valid++;
					}
				}

				if(item.parent().attr('class')){
					if($('.' + item.parent().attr('class').replace(/\n/g, '').trim()).length > 3){
						valid++;
					}
				}
			}catch(e){
				console.log(e);
			}

			if(href && valid > 0){
				hashes.push({
					string : href, 
					content : content.toLowerCase(), 
					classNames : classNames.toLowerCase(), 
					parentNode : item.parent().prop('tagName'),
					element : item 
				});
			}
		}

		return this.groupByHashes(hashes);
	}
};

Scraper.waitForLoad();