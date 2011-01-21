Ext.ns('monoql.data');
monoql.data.connectionstore = function() {
	var Record = Ext.data.Record.create([
		{name:'id', type:'string'},
		{name:'name', type:'string'},
		{name:'description', type:'string'},
		{name:'type', type:'string'},
		{name:'host', type:'string'},
		{name:'username', type:'string'},
		{name:'port', type:'int'},
		{name:'database', type:'string'}
	]);
	
	var Reader = Ext.extend(Ext.data.JsonReader, {
		constructor:function(meta, recordType) {
			meta = Ext.apply({
				root:'connections'
			}, meta);
			Reader.superclass.constructor.call(this, meta, Record);
		}
	});

	var Class = Ext.extend(Ext.data.Store, {
		constructor:function(config) {
			config = Ext.apply({
				autoLoad:true,
				url:monoql.url('/_services/connection/getconnections'),
				baseParams:{
					format:'json'
				},
				reader:new Reader()
			}, config);
			Class.superclass.constructor.call(this, config);
		}
	});
	return Class;
}();