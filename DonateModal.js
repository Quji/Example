define([
    'Form'
], function ()
{
    return {
        inject: ['Profile', 'Rest'],
        init: function()
        {
            this.$.Profile = this.Profile;

            this.$.Donate = this.Rest.model('/donate').$new();
            this.$.Donate.channel_id = this.$.Profile.id;

            this.$.PAYMENT_TYPE = this.$window.PAYMENT_TYPE;
        },
        methods: {
            onTabSelect: function( type )
            {
                this.$.Donate.type = type;
            },
            onSuccess: function()
            {
                if(this.$.Donate.id) {
                    this.$window.open('/index.php/payment/' + this.$.Donate.id, 'Redirect', "height=600,width=1000");
                }

                this.$.Donate.$pk = null;
            }
        }
    };
});
