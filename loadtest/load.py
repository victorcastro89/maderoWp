from locust import HttpLocust, TaskSequence,seq_task

header={
    "Host":"localhost",
    "User-Agent": "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0",
    "Accept":"*/*",
    "Accept-Language":"en-US,en;q=0.5",
    "Accept-Encoding":"gzip, deflate",
    "Referer":"http://localhost/?product=product-name-17",
    "Content-Type":"application/x-www-form-urlencoded; charset=UTF-8",
    "X-Requested-With":"XMLHttpRequest",
    "Content-Length": "85",
    "Connection": "keep-alive",
}
header1={
    "Host":"localhost",
    "User-Agent":"Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0",
    "Accept":"*/*",
    "Accept-Language":"en-US,en;q=0.5",
    "Accept-Encoding":"gzip, deflate",
    "Referer":"http://localhost/?product=product-name-17",
    "X-Requested-With":"XMLHttpRequest",
    "Connection":"keep-alive",
    "Cookie":"wordpress_test_cookie=WP+Cookie+check; wordpress_logged_in_86a9106ae65537651a8e456835b316ab=victor%7C1554514090%7CfIWJRrSX9uxMNXhpuoLhkAqmNF7jwJ9VwOQeH02pSHl%7C339467c9ec9691d79cb8140af3dd2d49ae9da432ea50bc696a9d7f37e5b53a3b; wp-settings-time-1=1554343332; laframework_active_section=popup_panel; woocommerce_recently_viewed=882; woocommerce_items_in_cart=1; woocommerce_cart_hash=77c982d09f79677022c4e3b4a3d34e42; wp_woocommerce_session_86a9106ae65537651a8e456835b316ab=1%7C%7C1554516210%7C%7C1554512610%7C%7C09c0e72709c2f6bcdbbb66196701028a",
    "Content-Length":"0",
}
class MyTaskSequence(TaskSequence):
    @seq_task(1)
    def first_task(self):
        self.client.get("/")
        pass

    @seq_task(2)
    def second_task(self):
        self.client.get("/produto/product-name-9/")
        pass

    @seq_task(3)
    def third_task(self):
        self.client.post("/produto/product-name-9/?product_quickview=1")#,json = header
        pass

    @seq_task(4)
    def buy(self):
        self.client.post("/?wc-ajax=get_refreshed_fragments")#,json = header1
        pass

    @seq_task(5)
    def checkout(self):
        self.client.get("/checkout")#,json = header
        pass

class WebsiteUser(HttpLocust):
    task_set = MyTaskSequence
    min_wait = 2000
    max_wait = 5000