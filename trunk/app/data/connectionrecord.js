Ext.ns('monoql.data');
monoql.data.connectionrecord = function() {
	var Class = Ext.data.Record.create([
		{name:'id', type:'int'},
		{name:'name', type:'string'},
		{name:'type', type:'string'},
		{name:'host', type:'string'},
		{name:'username', type:'string'},
		{name:'password', type:'string'},
		{name:'port', type:'int'},
		{name:'defaultDatabase', type:'string', mapping:'default_database'},
		{name:'mdate', type:'date'},
		{name:'cdate', type:'date'},
		{name:'deleted', type:'bool'},
		{name:'databases', type:'string'}
	]);
	return Class;
}();