Ext.ns('monoql.data');
monoql.data.connection = function() {
	var Class = function(config) {
		Ext.apply(this, config, {
			id:null,
			type:null,
			host:null,
			username:null,
			port:null,
			databases:[]
		});
	};
	return Class;
}();