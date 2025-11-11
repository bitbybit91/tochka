package marketplace

import (
	"github.com/gocraft/web"

	"github.com/bitbybit91/tochka/modules/util"
)

func (c *Context) AdminReviews(w web.ResponseWriter, r *web.Request) {
	// c.Reviews = GetAllReviews()
	util.RenderTemplate(w, "reviews/admin/reviews", c)
}
