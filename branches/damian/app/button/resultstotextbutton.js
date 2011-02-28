Ext.ns('monoql.button');
monoql.button.resultstotextbutton = function() {
	var cls = 'monoql-button-resultstotextbutton';
	var Class = Ext.extend(monoql.button.button, {
		iconCls:cls + '-icon',
		tooltip:'Display query results as text',
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