(function() {
    window.onload = function() {
        var self = null;
        var Jsearch = function() {
            self = this;
            this.version = 5;
            this.automatically = true;
            this.items = [];
            this.itemsFound = [];
            this.see = 10;
            this.getParameters = function() {
                var str = document.URL.split("?").pop();
                var obj = {};
                str.replace(/([^=&]+)=([^&]*)/g, function(m, key, value) {
                    obj[decodeURIComponent(key)] = decodeURIComponent(value);
                });
                return obj;
            };
            this.parameters = this.getParameters();
            this.init = function() {
                $('#loading').show();
                $('#js-search').submit(function(event) {
                    var validate = $('#js-input').val();
                    if (validate === "" || self.parameters.js === validate) {
                        event.preventDefault();
                    }
                });

                $(".pull-right").click(function() {
                    $('body').animate({scrollTop: 0}, 'slow');
                });

                $('#js-input').val(this.parameters.js.replace("+", " "));
                function getHTTPObject() {
                    if (typeof XMLHttpRequest !== 'undefined') {
                        return new XMLHttpRequest();
                    }
                    try {
                        return new ActiveXObject("Msxml2.XMLHTTP");
                    } catch (e) {
                        try {
                            return new ActiveXObject("Microsoft.XMLHTTP");
                        } catch (e) {
                        }
                    }
                    return false;
                }
                var url = null;
                if (this.automatically) {
                    url = "js/databasefolder.js?v=" + (new Date()).getTime();
                } else {
                    url = "js/database.js?v=" + (new Date()).getTime();
                }

                var http = getHTTPObject();
                http.open("GET", url, true);
                http.onreadystatechange = function() {
                    if (http.readyState === 4) {
                        self.items = JSON.parse(http.responseText);
                        self.search();
                    }
                };
                http.send(null);
            };
            this.search = function() {
                if (self.items.length > 0) {
                    var string = this.parameters;
                    var matchString = string.js;
                    var words = matchString.split("+");
                    for (var i in words) {
                        for (var k in self.items) {
                            if (self.items[k].title !== null && self.items[k].description !== null && self.items[k].description !== null && self.items[k].claves !== null)
                                if (self.items[k].title.toLowerCase().match(words[i].toLowerCase()) ||
                                        self.items[k].description.toLowerCase().match(words[i].toLowerCase()) ||
                                        self.items[k].claves.toLowerCase().match(words[i].toLowerCase())) {
                                    self.itemsFound.push(self.items[k]);
                                }
                        }

                        if (i == (words.length - 1)) {
                            if (self.itemsFound.length > 0) {
                                self.appendElements();
                            } else {
                                $('#js-alert-info .alert-info').html('No se ha encontrado coincidencias');
                                $('#js-alert-info').removeClass('js-display-none');
                                $('#loading').hide();
                            }
                        }
                    }
                } else {
                    console.log('No existen registros en la base de datos');
                }
            };
            this.appendElements = function() {
                var page = this.parameters.page;
                var from = this.see * (page - 1);
                var to = (this.see * (page) - 1);
                var elements = this.pagination(from, to);
                for (var i in elements) {
                    if (elements[i]) {
                        $.item = ('\
                            <div class="js-item">\
                                <div><a href="' + elements[i].link + '">' + elements[i].title + '</a></div>\
                                <div class="linkGreen">' + elements[i].link + '</div>\
                                <div>' + elements[i].description + '</div>\
                            </div>\
                        ');

                        $('#js-items-found').append($.item);
                    }
                }

                var prev = (parseInt(page) - 1);
                var next = (parseInt(page) + 1);
                var total = Math.ceil(this.itemsFound.length / this.see);
                $.pagination = '';
                if (page <= total && page > 0) {
                    if (next <= total) {
                        if (page == 1) {
                            $.pagination = ('\
                            <li class="disabled"><a>&laquo;</a></li>\
                            <li><a href="search.html?js=' + self.parameters.js + '&page=' + next + '">&raquo;</a></li>\
                        ');
                        } else {
                            $.pagination = ('\
                            <li><a href="search.html?js=' + self.parameters.js + '&page=' + prev + '">&laquo;</a></li>\
                            <li><a href="search.html?js=' + self.parameters.js + '&page=' + next + '">&raquo;</a></li>\
                        ');
                        }
                    } else {
                        if (page == 1) {
                            $.pagination = ('\
                            <li class="disabled"><a>&laquo;</a></li>\
                            <li class="disabled"><a>&raquo;</a></li>\
                        ');
                        } else {
                            $.pagination = ('\
                        <li><a href="search.html?js=' + self.parameters.js + '&page=' + prev + '">&laquo;</a></li>\
                        <li class="disabled"><a>&raquo;</a></li>\
                    ');
                        }
                    }

                    $('#js-current-page').html('P&aacute;gina ' + page + ' de ' + total);
                    $('.pagination').append($.pagination);
                    $('#loading').hide();
                } else {
                    $('#js-alert-info .alert-info').html('Esta p&aacute;gina no existe');
                    $('#js-alert-info').removeClass('js-display-none');
                    $('#loading').hide();
                }
            };
            this.pagination = function(from, to) {
                var obj = [];
                for (var i = from; i <= to; i++) {
                    obj.push(self.itemsFound[i]);
                }
                return obj;
            };
        };

        var search = new Jsearch();
        search.init();
    };
})();