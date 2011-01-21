Ext.ns('monoql.button');
monoql.button.addquerytabbutton = function() {
	var cls = 'monoql-button-addquerytabbutton';
	var Class = Ext.extend(monoql.button.button, {
		iconCls:cls + '-icon',
		tooltip:'Add a new query tab',
		enableToggle:true,
		allowDepress:true,
		initComponent: function() {
			this.on('render', this.onAddQueryTabButtonRender, this);
			Class.superclass.initComponent.call(this);
			this.addClass(cls);
		},
		onAddQueryTabButtonRender:function(button) {
			Ext.getCmp('viewport').tabs.addquerytabform.on('hide', this.onViewportTabsAddQueryTabFormHide, this);
		},
		onViewportTabsAddQueryTabFormHide:function(form) {
			this.toggle(false);
		}
	});
	Ext.reg(cls, Class);
	return Class;
}();