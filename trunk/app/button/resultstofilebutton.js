Ext.ns('monoql.button');
monoql.button.resultstofilebutton = function() {
	var cls = 'monoql-button-resultstofilebutton';
	var Class = Ext.extend(monoql.button.button, {
		iconCls:cls + '-icon',
		tooltip:'Save query results to file',
		enableToggle:true,
		toggleGroup:'monoql-button-resultstogglegroup',
		initComponent: function() {
			Class.superclass.initComponent.call(this);
			this.addClass(cls);
		}
	});
	Ext.reg(cls, Class);
	return Class;
}();