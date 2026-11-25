class PairDeskRequest:
    def __init__(self, session_secret, pairing_code):
        self.session_secret = session_secret
        self.pairing_code = pairing_code

class DeskMoveRequest:
    def __init__(self, session_secret, target_height_cm):
        self.session_secret = session_secret
        self.target_height_cm = target_height_cm

class RoomPresetRequest:
    def __init__(self, room_id, preset):
        self.room_id = room_id
        self.preset = preset

class UserPresetUpdate:
    def __init__(self, user_id, sit_height_cm, stand_height_cm):
        self.user_id = user_id
        self.sit_height_cm = sit_height_cm
        self.stand_height_cm = stand_height_cm