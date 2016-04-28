/*
 *  创建报名总体情况视图，详细显示每位选手的报名项目
 */
CREATE view player_engaged_item
as
SELECT player.id, player.name, player.phoneNum, man_single.id as man_single, man_double.firstId as man_double_firstId,
	   man_double.secondId as man_double_secondId, woman_single.id as woman_single, woman_double.firstId as woman_double_firstId,
       woman_double.secondId as woman_double_secondId, mix_double.firstId as mix_double_firstId, 
       mix_double.secondId as mix_double_secondId
from player LEFT JOIN man_single
			using (id)
            LEFT JOIN man_double
            on player.id = man_double.firstId or player.id = man_double.secondId
            LEFT JOIN woman_single
            using (id)
            LEFT JOIN woman_double
            on player.id = man_double.firstId or player.id = man_double.secondId
            LEFT JOIN mix_double
            on player.id = mix_double.firstId or player.id = mix_double.secondId