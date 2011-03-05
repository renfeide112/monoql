Ext.ns('monoql.window');
monoql.window.window = function() {
	var cls = 'monoql-window-window';
	var Class = Ext.extend(Ext.Window, {
		initComponent: function() {
			Class.superclass.initComponent.call(this);
			this.addClass(cls);
		}
	});
	Ext.reg(cls, Class);
	return Class;
}();