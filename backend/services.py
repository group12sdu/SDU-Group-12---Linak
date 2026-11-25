from database_connection import connect_main, connect_middleman

# this is our helper function for moving desks other functions will call it later
def send_move_to_desk(desk_id, target_height_cm):
    return {"desk_id": desk_id, "target_height_cm": target_height_cm}

def pair_desk(data):
    main_connection = connect_main()
    main_cursor = main_connection.cursor()
    main_cursor.execute("SELECT desk_token FROM desks WHERE pairing_code = %s", (data.pairing_code,))
    desk_row = main_cursor.fetchone()
    main_cursor.close()
    main_connection.close()

    if desk_row is None:
        return {"error": "wrong pairing code"}

    desk_token = desk_row[0]

    middleman_connection = connect_middleman()
    middleman_cursor = middleman_connection.cursor()

    # I delete old link first so if smoeone pairs twice with same session it doesnt crash
    middleman_cursor.execute("DELETE FROM anonymous_links WHERE session_hash = %s", (data.session_secret,))
    middleman_cursor.execute(
        "INSERT INTO anonymous_links (session_hash, desk_token, expires_at) VALUES (%s, %s, NOW() + INTERVAL '8 hours')",
        (data.session_secret, desk_token)
    )
    middleman_connection.commit()
    middleman_cursor.close()
    middleman_connection.close()
    return {"pairing_code": data.pairing_code}

def move_desk(data):
    middleman_connection = connect_middleman()
    middleman_cursor = middleman_connection.cursor()
    
    # I added expires_at > NOW() here so expired sessions cant move the desk anymore
    middleman_cursor.execute(
        "SELECT desk_token FROM anonymous_links WHERE session_hash = %s AND expires_at > NOW()",
        (data.session_secret,)
    )
    link_row = middleman_cursor.fetchone()
    middleman_cursor.close()
    middleman_connection.close()

    if link_row is None:
        return {"error": "not paired"}

    desk_token = link_row[0]

    main_connection = connect_main()
    main_cursor = main_connection.cursor()
    main_cursor.execute("SELECT desk_id FROM desks WHERE desk_token = %s", (desk_token,))
    desk_row = main_cursor.fetchone()
    main_cursor.close()
    main_connection.close()

    if desk_row is None:
        return {"error": "desk not found"}

    return send_move_to_desk(desk_row[0], data.target_height_cm)

def apply_room_preset(data):
    main_connection = connect_main()
    main_cursor = main_connection.cursor()
    main_cursor.execute(
        "SELECT cleaning_up_cm, cleaning_down_cm, pretty_picture_cm FROM room_presets WHERE room_id = %s",
        (data.room_id,)
    )
    preset_row = main_cursor.fetchone()

    if preset_row is None:
        main_cursor.close()
        main_connection.close()
        return {"error": "room not found"}

    # I mapped teh preset name to column value and loop all desks in room through the same move helper
    preset_heights = {
        "cleaning_up": preset_row[0],
        "cleaning_down": preset_row[1],
        "pretty_picture": preset_row[2]
    }
    if data.preset not in preset_heights:
        main_cursor.close()
        main_connection.close()
        return {"error": "invalid preset"}

    target_height = preset_heights[data.preset]

    main_cursor.execute("SELECT desk_id FROM desks WHERE room_id = %s", (data.room_id,))
    desk_rows = main_cursor.fetchall()
    main_cursor.close()
    main_connection.close()

    moved_desks = []
    for row in desk_rows:
        moved_desks.append(send_move_to_desk(row[0], target_height))

    return {
        "room_id": data.room_id,
        "preset": data.preset,
        "moved_desks": moved_desks
    }

def get_user_presets(user_id):
    main_connection = connect_main()
    main_cursor = main_connection.cursor()
    main_cursor.execute("SELECT sit_height_cm, stand_height_cm FROM user_presets WHERE user_id = %s", (user_id,))
    preset_row = main_cursor.fetchone()
    main_cursor.close()
    main_connection.close()

    if preset_row is None:
        return {"error": "user not found"}
    return {
        "user_id": user_id,
        "sit_height_cm": preset_row[0],
        "stand_height_cm": preset_row[1]
    }

def update_user_presets(data):
    main_connection = connect_main()
    main_cursor = main_connection.cursor()
    main_cursor.execute(
        "UPDATE user_presets SET sit_height_cm = %s, stand_height_cm = %s WHERE user_id = %s",
        (data.sit_height_cm, data.stand_height_cm, data.user_id)
    )
    
    # I check rowcount here so if the user doesnt exist it wont pretend it updated
    if main_cursor.rowcount == 0:
        main_cursor.close()
        main_connection.close()
        return {"error": "user not found"}

    main_connection.commit()
    main_cursor.close()
    main_connection.close()
    return {"user_id": data.user_id}