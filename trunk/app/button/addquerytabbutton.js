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
			this.on('toggle', this.onToggle, this);
			Class.superclass.initComponent.call(this);
			this.addClass(cls);
		},
		onAddQueryTabButtonRender:function(button) {
			Ext.getCmp('viewport').addquerytabform.on('hide', this.onUiAddQueryTabFormHide, this);
		},
		onUiAddQueryTabFormHide:function(form) {
			this.toggle(false);
		},
		onToggle:function(button, pressed) {
			if (pressed) {
				if (!ui.addquerytabform.rendered) {
					ui.addquerytabform.render(Ext.getBody());
				}
				Ext.QuickTips.getQuickTip().hide();
				ui.addquerytabform.show().el.anchorTo(ui.toolbar.addquerytabbutton.el, 'tl-bl');
			} else {
				ui.addquerytabform.hide();
			}
		}
	});
	Ext.reg(cls, Class);
	return Class;
}();