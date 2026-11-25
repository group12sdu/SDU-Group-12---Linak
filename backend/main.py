from flask import Flask, request, jsonify
import models
import services

app = Flask(__name__)


@app.errorhandler(Exception)
def handle_error(e):
    return jsonify({"error": str(e)}), 500


@app.route("/api/health", methods=["GET"])
def health():
    return jsonify({"status": "ok"}), 200


@app.route("/api/desks/pair", methods=["POST"])
def pair():
    data = request.get_json()
    req = models.PairDeskRequest(data["session_secret"], data["pairing_code"])
    return jsonify(services.pair_desk(req)), 201


@app.route("/api/desks/move", methods=["PUT"])
def move():
    data = request.get_json()
    req = models.DeskMoveRequest(data["session_secret"], data["target_height_cm"])
    return jsonify(services.move_desk(req)), 200


@app.route("/api/rooms/preset", methods=["POST"])
def preset():
    data = request.get_json()
    req = models.RoomPresetRequest(data["room_id"], data["preset"])
    return jsonify(services.apply_room_preset(req)), 200


@app.route("/api/users/<int:user_id>/presets", methods=["GET"])
def get_presets(user_id):
    return jsonify(services.get_user_presets(user_id)), 200


@app.route("/api/users/presets", methods=["PUT"])
def update_presets():
    data = request.get_json()
    req = models.UserPresetUpdate(data["user_id"], data["sit_height_cm"], data["stand_height_cm"])
    return jsonify(services.update_user_presets(req)), 200


if __name__ == "__main__":
    app.run(host="0.0.0.0", port=8001, debug=True)