def pair_desk(data):
    return {"pairing_code": data.pairing_code}


def move_desk(data):
    return {"target_height_cm": data.target_height_cm}


def apply_room_preset(data):
    return {"room_id": data.room_id, "preset": data.preset}


def get_user_presets(user_id):
    return {"user_id": user_id, "sit_height_cm": 75, "stand_height_cm": 110}


def update_user_presets(data):
    return {"user_id": data.user_id}