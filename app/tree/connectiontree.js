Ext.ns('monoql.tree');
monoql.tree.connectiontree = function() {
	var cls = 'monoql-tree-connectiontree';
	var Class = Ext.extend(monoql.tree.tree, {
		width:250,
		title:'Data Connections',
		headerStyle:'border-top-width:0px;border-left-width:0px;',
		bodyStyle:'border-left-width:0px;',
		animate:false,
		root:{
			nodeType:'monoql-tree-connectiongroupnode',
			id:'root-connectiongroup',
			text:'Connections'
		},
		rootVisible:true,
		initComponent: function() {
			this.tbar = new monoql.bar.connectiontreetoolbar();
			this.loader = new monoql.tree.connectiontreeloader();
			this.tbar.refreshbutton.addListener('click', this.onToolbarRefreshButtonClick, this);
			this.tbar.newconnectionbutton.on('toggle', this.onToolBarNewConnectionButtonToggle, this);
			Ext.getCmp('viewport').newconnectionform.on('hide', this.onViewportNewConnectionFormHide, this);
			Class.superclass.initComponent.call(this);
			this.getSelectionModel().on('selectionchange', this.getTopToolbar().refreshbutton.onConnectionTreeSelectionChange, this.getTopToolbar().refreshbutton);
			this.addClass(cls);
		},
		onViewportNewConnectionFormHide:function(form) {
			if (this.getTopToolbar().newconnectionbutton.pressed) {
				this.getTopToolbar().newconnectionbutton.toggle(false);
			}
		},
		onToolBarNewConnectionButtonToggle:function(button, pressed) {
			if (pressed) {
				if (!ui.newconnectionform.rendered) {
					ui.newconnectionform.render(Ext.getBody());
				}
				ui.newconnectionform.show().el.anchorTo(button.el, 'tl-bl');
			} else {
				ui.newconnectionform.hide();
			}
		},
		onToolbarRefreshButtonClick:function(button, event) {
			var selectedNode = this.getSelectionModel().getSelectedNode();
			if (selectedNode && selectedNode.reload) {
				selectedNode.reload();
			}
		}
	});
	Ext.reg(cls, Class);
	return Class;
}();