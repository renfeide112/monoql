Ext.ns('monoql.tab');
monoql.tab.maintabset = function() {
	var cls = 'monoql-tab-maintabset';
	var queryTabCount = 0;
	var Class = Ext.extend(monoql.tab.tabset, {
		headerStyle:'border-top-width:0px;',
		initComponent: function() {
			this.addquerytabform = new monoql.form.addquerytabform({
				hidden:true
			});
			Ext.getCmp('viewport').toolbar.addquerytabbutton.on('toggle', this.onUiToolBarAddQueryTabButtonToggle, this);
			this.addquerytabform.on('show', this.onAddQueryTabFormShow, this);
			this.items = [];
			Class.superclass.initComponent.call(this);
			this.addClass(cls);
		},
		onAddQueryTabFormShow:function(form) {
			
		},
		onUiToolBarAddQueryTabButtonToggle:function(button, pressed) {
			if (pressed) {
				if (!this.addquerytabform.rendered) {
					this.addquerytabform.render(Ext.getBody());
				}
				this.addquerytabform.show().el.anchorTo(ui.toolbar.addquerytabbutton.el, 'tl-bl');
			} else {
				this.addquerytabform.hide();
			}
		},
		addQueryTab:function(connection) {
			if (connection) {
				var tab = new monoql.tab.querytab({
					index:++queryTabCount
				});
				this.activate(this.add(tab));
				return tab;
			}
		}
	});
	Ext.reg(cls, Class);
	return Class;
}();