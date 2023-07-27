namespace WebRequestRepeater;

public partial class MainTabPage : TabbedPage
{
	public MainTabPage()
	{
		InitializeComponent();

		Title = "MyOPECS W2R Attack Tools";

		ToolbarItems.Add(new ToolbarItem
		{
			Text = "About",
			Command = new Command(() =>
			{
				Navigation.PushAsync(new Pages.AboutPage());
			}),
			Order = ToolbarItemOrder.Secondary
		});

		Children.Add(new Pages.TargetPage());
		Children.Add(new Pages.RequestPage());
		Children.Add(new Pages.LaunchingPage());
	}
}