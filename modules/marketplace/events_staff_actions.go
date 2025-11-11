package marketplace

import (
	"fmt"

	"github.com/bitbybit91/tochka/modules/apis"
)

func EventNewStaffToUserAction(staff, user User, action string) {
	var (
		marketUrl = MARKETPLACE_SETTINGS.SiteURL
		text      = fmt.Sprintf("[@%s](%s/user/%s) has %s [@%s](%s/user/%s)",
			staff.Username, marketUrl, staff.Username,
			action,
			user.Username, marketUrl, user.Username,
		)
	)
	go apis.PostMattermostEvent(MARKETPLACE_SETTINGS.MattermostIncomingHookStaffActions, text)
}
