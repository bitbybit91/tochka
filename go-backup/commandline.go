package main

import (
	"fmt"

	"github.com/bitbybit91/tochka/modules/marketplace"
	"github.com/bitbybit91/tochka/modules/util"
)

func manageRole(username, action, role string) {
	user, _ := marketplace.FindUserByUsername(username)
	if user == nil {
		fmt.Println("No such user")
		return
	}
	if action == "grant" && role == "seller" {
		user.IsSeller = !user.IsSeller
	} else if action == "grant" && role == "admin" {
		user.IsAdmin = !user.IsAdmin
	} else {
		fmt.Println("Wrong action")
		return
	}
	user.Save()
}

func indexItems() {
	util.Log.Debug("[Index] Indexing items...")
	for _, item := range marketplace.GetAllItems() {
		util.Log.Debug("[Index] %s", item.Name)
		err := item.Index()
		if err != nil {
			util.Log.Error("%s", err)
		}
	}
}

func syncModels() {
	marketplace.SyncModels()
}

func staffStats() {
	interval := "2018-09-07 13:32"
	sTickets, err := marketplace.StaffSupportTicketsResolutionStats(interval)
	if err != nil {
		return
	}
	sDisputes, err := marketplace.StaffSupportDisputesResolutionStats(interval)
	if err != nil {
		return
	}
	sItems, err := marketplace.StaffItemApprovalStats(interval)
	if err != nil {
		return
	}

	var (
		text = fmt.Sprintf(
			`
Support Agent | Ticket Status | Number Of Tickets
--- | --- | ---
`)
	)
	for _, si := range sTickets {
		text += fmt.Sprintf("%s | TICKET %s | %d\n", si.ResolverUsername, si.CurrentStatus, si.TicketCount)
	}
	for _, si := range sDisputes {
		text += fmt.Sprintf("%s | DISPUTE %s | %d\n", si.ResolverUsername, si.CurrentStatus, si.TicketCount)
	}
	for _, si := range sItems {
		text += fmt.Sprintf("%s | ITEM %s | %d\n", si.ResolverUsername, si.CurrentStatus, si.TicketCount)
	}

	println(text)
}

func importMetroStations() {
	marketplace.ImportCityMetroStations(524901, "./dumps/moscow-metro.json")
	marketplace.ImportCityMetroStations(498817, "./dumps/spb-metro.json")
}

func seedMockUsers() {
	mockUsers := []string{
		"Plugutopia",
		"Hofmanncrew",
		"Merckgrade",
		"UAEDROPS",
		"Norcalgreat",
		"ozdope",
		"Roaryohara",
		"Chembros",
		"Dankorignal",
		"JohnAlite",
		"Chadfontain",
		"Grimbastard",
		"Paladin",
		"Potpacks",
		"StrainPirate",
		"BERGHAIN",
		"Kushmountain",
		"Stoopchild20",
		"Bostongeorge",
	}

	defaultPassword := "password123"
	
	for _, username := range mockUsers {
		user, err := marketplace.CreateUser(username, defaultPassword)
		if err != nil {
			fmt.Printf("Error creating user %s: %v\n", username, err)
			continue
		}
		// Make them sellers
		user.IsSeller = true
		user.Save()
		fmt.Printf("Created mock user: %s\n", username)
	}
	
	fmt.Println("Mock users seeding completed")
}

func seedMockListings() {
	mockUsers := []string{
		"Plugutopia", "Hofmanncrew", "Merckgrade", "UAEDROPS", "Norcalgreat",
		"ozdope", "Roaryohara", "Chembros", "Dankorignal", "JohnAlite",
		"Chadfontain", "Grimbastard", "Paladin", "Potpacks", "StrainPirate",
		"BERGHAIN", "Kushmountain", "Stoopchild20", "Bostongeorge",
	}

	mockListings := []struct {
		name        string
		description string
		price       float64
	}{
		{"Cali Kush [A+++]", "🤯 40 telegram:calibudog: HYBRID, INDICA, SATIVA❗️\n🛒 Our oil is FREE OF PESTICIDES. NO FILLERS OR HEAVY METALS. Check our LABS ❗️\n📦Packages are tracked, stealth, legit\nInternational shipping with 100% reship guarantee", 10.00},
		{"Gelato 41 [A+++] 100g", "Premium Gelato 41, lab tested, tracked shipping\n🛒 FREE OF PESTICIDES. NO FILLERS OR HEAVY METALS\n📦Tracked, stealth packaging\nInternational orders ship within 24 hours", 1600.00},
		{"Gelato 41 [A+++] 500g", "Bulk Gelato 41, international shipping available\n🤯 TOP QUALITY - Lab tested\n📦Guaranteed delivery and satisfaction\nTracking provided. 100% reship if undelivered", 2475.00},
		{"Amnesia Haze [A+++]", "Top quality Amnesia Haze, Hybrid strain\n🛒 NO FILLERS OR HEAVY METALS\n📦Stealth, legit packaging\nInternational shipping available", 550.00},
		{"White Widow [A+++]", "Classic White Widow strain, stealth packaging\n🤯 Premium quality - Lab tested\n📦Tracked shipping worldwide", 550.00},
		{"Super Silver Haze [A+++]", "Premium Super Silver Haze, guaranteed delivery\n🛒 FREE OF PESTICIDES\n📦International orders ship within 24 hours", 550.00},
		{"OG Kush [A+++]", "Original OG Kush, tracked international shipping\n🤯 TOP SHELF QUALITY\n📦Stealth packaging, tracked delivery", 550.00},
		{"Lemon Kush [A+++]", "Fresh Lemon Kush, pesticide-free, lab tested\n🛒 Premium quality guaranteed\n📦International shipping with tracking", 550.00},
		{"Trainwreck [A+++]", "High quality Trainwreck, stealth packaging\n🤯 Sativa dominant hybrid\n📦Tracked, guaranteed delivery", 550.00},
		{"Northern Lights [A+++]", "Classic Northern Lights strain, tracked shipping\n🛒 Lab tested, pesticide-free\n📦International shipping available", 550.00},
		{"Green Mountain Extracts", "Premium extracts, free of pesticides\n🤯 TOP QUALITY EXTRACTS\n📦Stealth packaging, tracked delivery", 550.00},
		{"Ketama Hash [A++ THC 45%]", "High THC Ketama hash, 100% reship guarantee\n🛒 THC 45% - Lab tested\n📦International shipping with tracking", 550.00},
		{"Kosher Kush Hash [A++ THC 45%]", "Premium Kosher Kush hash, tracked delivery\n🤯 THC 45% guaranteed\n📦Stealth packaging worldwide", 550.00},
		{"Ice-O-Lator Hash 1000g", "Top quality Ice-O-Lator hash, lab tested\n🛒 Premium hash - 1kg\n📦Tracked international shipping", 5500.00},
		{"Girl Scout Cookies Hash", "GSC hash, premium quality, stealth shipping\n🤯 TOP SHELF HASH\n📦Guaranteed delivery worldwide", 5500.00},
		{"Mochi Gelato [A++] 100kg", "Bulk Mochi Gelato, international shipping\n🛒 WHOLESALE QUANTITY - 100kg\n📦Tracked shipping, guaranteed delivery", 220000.00},
	}

	for _, username := range mockUsers {
		user, _ := marketplace.FindUserByUsername(username)
		if user == nil {
			fmt.Printf("User %s not found, skipping listings\n", username)
			continue
		}

		// Create items for this user
		itemCount := 0
		for _, listing := range mockListings {
			item := marketplace.Item{
				Uuid:           util.GenerateUuid(),
				Name:           listing.name,
				Description:    listing.description,
				UserUuid:       user.Uuid,
				ItemCategoryID: 1, // Default category
			}

			err := item.SaveToDatabase()
			if err != nil {
				fmt.Printf("Error creating item %s for %s: %v\n", listing.name, username, err)
				continue
			}

			// Create package for the item
			pkg := marketplace.Package{
				Uuid:                      util.GenerateUuid(),
				Name:                      listing.name,
				Description:               "International shipping available. Tracked and stealth packaging.",
				Type:                      "mail",
				ItemUuid:                  item.Uuid,
				CountryNameEnShippingFrom: "WORLDWIDE",
				CountryNameEnShippingTo:   "WORLDWIDE",
			}

			err = pkg.SaveToDatabase()
			if err != nil {
				fmt.Printf("Error creating package for %s: %v\n", listing.name, err)
				continue
			}

			// Set package price
			price := marketplace.PackagePrice{
				Uuid:     pkg.Uuid,
				Currency: "EUR",
				Price:    listing.price,
			}
			err = price.Save()
			if err != nil {
				fmt.Printf("Error setting price for %s: %v\n", listing.name, err)
				continue
			}
			
			itemCount++
		}
		
		fmt.Printf("Created %d listings for user: %s\n", itemCount, username)
	}
	
	fmt.Println("Mock listings seeding completed")
}
