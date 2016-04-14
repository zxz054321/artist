折腾了多年的博客程序，从最早的新浪博客、百度空间、网易博客等博客平台，到早年的 ZBlog、PJBlog、Emolog之类的国产博客程序，再到目前用得最久的 WordPress……一直都不太满意，从没停止过纠结。总体来说，似乎折腾博客用了更多的时间，累觉不爱。
<!--more-->
博客平台虽然免费易用，但有个逼死处女座的地方——要么有很多广告，要么一进去就弹个大大的框要人注册登录还不给人关掉，这个真忍无可忍。

国产博客程序，其实还是挺好用的，容易上手，内置功能强大。但它们缺乏强大的社区支持，后期发展缺乏动力，随着 WordPress 在国内兴起，慢慢的就冷清了。

WordPress 呢，全球最流行的开源博客程序，的确强大。就是历史包袱太重，代码仓库还坚守自己的那个SVN，放在 GitHub 上的居然只是个镜像。程序还是上个时代的架构，有些跟不上日新月异的云时代。最最重要的是，WordPress 在迁移服务器时无比麻烦。特别是图片等文件，默认本地储存。久而久之，文件体积巨大，数据库也不小，迁移时非常耗时，很容易出错。

经过一番大范围的对比研究，我发现 GIT 其实可以当作一个非常好的内容管理系统，自带版本控制、分支管理，简直是内容管理神器。而且网络上有很多免费的 GIT 服务，结合静态页面生成器，博客部署、迁移和备份成本都非常低。

最赞的是，基于 GIT 的博客方案，几乎不需要运维，平时不需要去担心服务器有没有挂，程序有没有爆安全漏洞，哪里又出没出问题。我并不常写东西，只是想安安静静的有一个博客，时不时装下逼。