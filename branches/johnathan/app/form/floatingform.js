Ext.ns('monoql.form');
monoql.form.floatingform = function() {
	var cls = 'monoql-form-floatingform';
	var Class = Ext.extend(monoql.form.form, {
		floating:true,
		autoHeight:true,
		width:600,
		labelAlign:'top',
		frame:true,
		initComponent: function() {
			this.tools = [{
				id:'close',
				handler:this.onCloseToolClick,
				scope:this
			}];
			Class.superclass.initComponent.call(this);
			this.addClass(cls);
		},
		onCloseToolClick:function() {
			this.hide();
		}
	});
	Ext.reg(cls, Class);
	return Class;
}();